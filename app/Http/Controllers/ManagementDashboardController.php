<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ManagementDashboardController extends Controller
{
    public function index(): View
    {
        $today = now()->toDateString();

        $summary = [
            'daily_revenue' => (float) DB::table('transaksi')
                ->where('status_transaksi', 'Approved')
                ->whereDate('tanggal_transaksi', $today)
                ->sum('nominal_belanja'),
            'total_members' => DB::table('pelanggan')->where('is_active', 1)->count(),
            'approved_receipts' => DB::table('transaksi')->where('status_transaksi', 'Approved')->count(),
            'redeem_count' => DB::table('penukaran_poin')->count(),
        ];

        $weeklyUploads = DB::table('transaksi')
            ->selectRaw('DATE(created_at) as upload_date, COUNT(*) as total')
            ->whereDate('created_at', '>=', now()->subDays(6)->toDateString())
            ->groupByRaw('DATE(created_at)')
            ->orderBy('upload_date')
            ->get()
            ->keyBy('upload_date');

        $weeklyTrend = collect(range(6, 0))
            ->map(fn (int $daysAgo): array => [
                'label' => now()->subDays($daysAgo)->format('d M'),
                'total' => (int) ($weeklyUploads[now()->subDays($daysAgo)->toDateString()]->total ?? 0),
            ])
            ->push([
                'label' => now()->format('d M'),
                'total' => (int) ($weeklyUploads[$today]->total ?? 0),
            ]);

        $clusterDistribution = DB::table('cluster')
            ->leftJoin('pelanggan', 'cluster.id_cluster', '=', 'pelanggan.id_cluster')
            ->select('cluster.id_cluster', 'cluster.nama_cluster', DB::raw('COUNT(pelanggan.id_pelanggan) as total'))
            ->groupBy('cluster.id_cluster', 'cluster.nama_cluster')
            ->orderBy('cluster.id_cluster')
            ->get();

        $loyalty = [
            'distributed' => (int) DB::table('transaksi')
                ->where('status_transaksi', 'Approved')
                ->sum('poin_yang_didapat'),
            'redeemed' => (int) DB::table('penukaran_poin')->sum('poin_terpotong'),
        ];

        $topTenants = DB::table('tenant')
            ->leftJoin('transaksi', function ($join): void {
                $join->on('tenant.id_tenant', '=', 'transaksi.id_tenant')
                    ->where('transaksi.status_transaksi', '=', 'Approved');
            })
            ->select(
                'tenant.id_tenant',
                'tenant.nama_tenant',
                DB::raw('COUNT(transaksi.id_transaksi) as total_transaksi'),
                DB::raw('COALESCE(SUM(transaksi.nominal_belanja), 0) as revenue'),
            )
            ->groupBy('tenant.id_tenant', 'tenant.nama_tenant')
            ->orderByDesc('revenue')
            ->limit(8)
            ->get();

        return view('mgmt.dashboard', compact(
            'summary',
            'weeklyTrend',
            'clusterDistribution',
            'loyalty',
            'topTenants',
        ));
    }

    public function exportTenants(): StreamedResponse
    {
        $rows = DB::table('tenant')
            ->leftJoin('transaksi', function ($join): void {
                $join->on('tenant.id_tenant', '=', 'transaksi.id_tenant')
                    ->where('transaksi.status_transaksi', '=', 'Approved');
            })
            ->select(
                'tenant.nama_tenant',
                DB::raw('COUNT(transaksi.id_transaksi) as total_transaksi'),
                DB::raw('COALESCE(SUM(transaksi.nominal_belanja), 0) as pendapatan'),
            )
            ->groupBy('tenant.id_tenant', 'tenant.nama_tenant')
            ->orderByDesc('pendapatan')
            ->get();

        return $this->csv('laporan-performa-tenant.csv', [
            'Tenant',
            'Total Transaksi Approved',
            'Pendapatan',
        ], $rows->map(fn (object $row): array => [
            $row->nama_tenant,
            $row->total_transaksi,
            $row->pendapatan,
        ]));
    }

    public function exportMembers(): StreamedResponse
    {
        $rows = DB::table('pelanggan')
            ->leftJoin('cluster', 'pelanggan.id_cluster', '=', 'cluster.id_cluster')
            ->leftJoin('transaksi', function ($join): void {
                $join->on('pelanggan.id_pelanggan', '=', 'transaksi.id_pelanggan')
                    ->where('transaksi.status_transaksi', '=', 'Approved');
            })
            ->select(
                'pelanggan.no_pelanggan',
                'pelanggan.nama_pelanggan',
                'pelanggan.email_pelanggan',
                'cluster.nama_cluster',
                'pelanggan.total_poin',
                DB::raw('COUNT(transaksi.id_transaksi) as transaksi_approved'),
                DB::raw('COALESCE(SUM(transaksi.nominal_belanja), 0) as total_belanja'),
            )
            ->groupBy(
                'pelanggan.id_pelanggan',
                'pelanggan.no_pelanggan',
                'pelanggan.nama_pelanggan',
                'pelanggan.email_pelanggan',
                'cluster.nama_cluster',
                'pelanggan.total_poin',
            )
            ->orderByDesc('total_belanja')
            ->get();

        return $this->csv('laporan-aktivitas-member.csv', [
            'No Pelanggan',
            'Nama',
            'Email',
            'Cluster',
            'Total Poin',
            'Transaksi Approved',
            'Total Belanja',
        ], $rows->map(fn (object $row): array => [
            $row->no_pelanggan,
            $row->nama_pelanggan,
            $row->email_pelanggan,
            $row->nama_cluster ?? 'Belum disegmentasi',
            $row->total_poin,
            $row->transaksi_approved,
            $row->total_belanja,
        ]));
    }

    private function csv(string $filename, array $headers, iterable $rows): StreamedResponse
    {
        return response()->streamDownload(function () use ($headers, $rows): void {
            $output = fopen('php://output', 'wb');
            fwrite($output, "\xEF\xBB\xBF");
            fputcsv($output, $headers);

            foreach ($rows as $row) {
                fputcsv($output, $row);
            }

            fclose($output);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}
