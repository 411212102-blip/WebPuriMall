<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function index(Request $request): View
    {
        $pegawai = auth('pegawai')->user();
        $status = $request->query('status', 'Pending');
        abort_if(! in_array($status, ['Pending', 'Approved', 'Rejected'], true), 404);

        $pendingTransaksi = Transaksi::query()
            ->with([
                'pelanggan:id_pelanggan,nama_pelanggan',
                'tenant:id_tenant,nama_tenant',
            ])
            ->where('status_transaksi', 'Pending')
            ->oldest('created_at')
            ->get();

        $filteredTransaksi = Transaksi::query()
            ->with([
                'pelanggan:id_pelanggan,nama_pelanggan',
                'tenant:id_tenant,nama_tenant',
                'pegawai:id_pegawai,nama_pegawai',
            ])
            ->where('status_transaksi', $status)
            ->latest('updated_at')
            ->paginate(12)
            ->withQueryString();

        $clusterCounts = [
            1 => \App\Models\Pelanggan::where('id_cluster', 1)->count(),
            2 => \App\Models\Pelanggan::where('id_cluster', 2)->count(),
            3 => \App\Models\Pelanggan::where('id_cluster', 3)->count(),
        ];

        $totalClustered = array_sum($clusterCounts);

        $activeEvents = DB::table('event')
            ->where('is_active', 1)
            ->whereDate('tgl_mulai', '<=', now()->toDateString())
            ->whereDate('tgl_selesai', '>=', now()->toDateString())
            ->orderBy('tgl_selesai')
            ->limit(4)
            ->get(['id_event', 'nama_event', 'lokasi', 'tgl_mulai', 'tgl_selesai']);

        $upcomingEvents = DB::table('event')
            ->where('is_active', 1)
            ->whereDate('tgl_mulai', '>', now()->toDateString())
            ->orderBy('tgl_mulai')
            ->limit(3)
            ->get(['id_event', 'nama_event', 'lokasi', 'tgl_mulai', 'tgl_selesai']);

        $facilityStats = [
            'total' => DB::table('fasilitas')->count(),
            'by_floor' => DB::table('fasilitas')
                ->select('lokasi_lantai', DB::raw('COUNT(*) as total'))
                ->groupBy('lokasi_lantai')
                ->orderByDesc('total')
                ->limit(4)
                ->get(),
        ];

        $parkingStats = [
            'total_capacity' => (int) DB::table('parkir')->sum('kapasitas'),
            'member_slots' => (int) DB::table('parkir')->where('tipe_parkir', 'Member')->sum('kapasitas'),
            'vip_slots' => (int) DB::table('parkir')->where('tipe_parkir', 'VIP')->sum('kapasitas'),
            'by_vehicle' => DB::table('parkir')
                ->select('jenis_kendaraan', DB::raw('SUM(kapasitas) as total'))
                ->groupBy('jenis_kendaraan')
                ->orderBy('jenis_kendaraan')
                ->get(),
        ];

        $redeemStats = [
            'today' => DB::table('penukaran_poin')->whereDate('tanggal_redeem', now()->toDateString())->count(),
            'points_spent_month' => (int) DB::table('penukaran_poin')
                ->whereYear('tanggal_redeem', now()->year)
                ->whereMonth('tanggal_redeem', now()->month)
                ->sum('poin_terpotong'),
            'latest' => DB::table('penukaran_poin')
                ->join('pelanggan', 'penukaran_poin.id_pelanggan', '=', 'pelanggan.id_pelanggan')
                ->join('katalog_hadiah', 'penukaran_poin.id_hadiah', '=', 'katalog_hadiah.id_hadiah')
                ->orderByDesc('tanggal_redeem')
                ->limit(5)
                ->get([
                    'penukaran_poin.id_redeem',
                    'penukaran_poin.tanggal_redeem',
                    'penukaran_poin.poin_terpotong',
                    'pelanggan.nama_pelanggan',
                    'katalog_hadiah.nama_hadiah',
                ]),
        ];

        $rfmTopCustomers = DB::table('vw_rfm_pelanggan')
            ->orderByDesc('monetary')
            ->limit(5)
            ->get(['id_pelanggan', 'nama_pelanggan', 'recency_days', 'frequency', 'monetary', 'total_poin']);

        return view('admin.dashboard', compact(
            'pegawai',
            'status',
            'pendingTransaksi',
            'filteredTransaksi',
            'clusterCounts',
            'totalClustered',
            'activeEvents',
            'upcomingEvents',
            'facilityStats',
            'parkingStats',
            'redeemStats',
            'rfmTopCustomers',
        ));
    }

    public function approve(int $id): RedirectResponse
    {
        set_time_limit(0);

        $transaksi = DB::transaction(function () use ($id) {
            $transaksi = Transaksi::query()
                ->with('pelanggan')
                ->where('status_transaksi', 'Pending')
                ->lockForUpdate()
                ->findOrFail($id);

            $poin = (int) floor(((float) $transaksi->nominal_belanja) / 10000);

            $transaksi->update([
                'status_transaksi' => 'Approved',
                'id_pegawai' => auth('pegawai')->id(),
                'poin_yang_didapat' => $poin,
                'catatan_tolak' => null,
            ]);

            $transaksi->pelanggan?->increment('total_poin', $poin);

            return $transaksi->fresh();
        });

        $output = $this->runKMeansWorker();

        Log::info('Transaksi approved and K-Means worker executed.', [
            'id_transaksi' => $transaksi->id_transaksi,
            'id_pegawai' => auth('pegawai')->id(),
            'python_output' => $output,
        ]);

        return back()->with('success', 'Struk disetujui, poin otomatis dihitung, dan K-Means analytics berhasil dipicu.');
    }

    public function reject(Request $request, int $id): RedirectResponse
    {
        $data = $request->validate([
            'catatan_tolak' => ['required', 'string', 'max:1000'],
        ]);

        $transaksi = Transaksi::query()
            ->where('status_transaksi', 'Pending')
            ->findOrFail($id);

        $transaksi->update([
            'status_transaksi' => 'Rejected',
            'id_pegawai' => auth('pegawai')->id(),
            'catatan_tolak' => $data['catatan_tolak'],
        ]);

        Log::info('Transaksi rejected without K-Means execution.', [
            'id_transaksi' => $transaksi->id_transaksi,
            'id_pegawai' => auth('pegawai')->id(),
            'catatan_tolak' => $data['catatan_tolak'],
        ]);

        return back()->with('success', 'Struk ditolak. Data tidak dikirim ke proses K-Means.');
    }

    private function runKMeansWorker(): string
    {
        $pythonPath = '"C:\Users\yells\AppData\Local\Python\pythoncore-3.14-64\python.exe"';
        $scriptPath = '"C:\xampp\htdocs\Web-Puri-Mall\analytics\kmeans_worker.py"';
        $command = $pythonPath . ' ' . $scriptPath . ' 2>&1';

        $output = shell_exec($command) ?? 'Python worker returned no output.';
        Storage::disk('local')->append('logs/kmeans_worker_output.log', now() . PHP_EOL . $output . PHP_EOL);

        return $output;
    }
}
