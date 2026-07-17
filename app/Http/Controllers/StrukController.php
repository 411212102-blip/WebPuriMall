<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\Transaksi;
use Illuminate\Support\Facades\File;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class StrukController extends Controller
{
    public function showForm(): View
    {
        $riwayatStruk = Transaksi::query()
            ->with('tenant:id_tenant,nama_tenant')
            ->where('id_pelanggan', auth('pelanggan')->id())
            ->latest('created_at')
            ->get();

        $tenants = Tenant::query()
            ->where('is_active', 1)
            ->orderBy('nama_tenant')
            ->get(['id_tenant', 'nama_tenant']);

        return view('customer.upload-struk', compact('riwayatStruk', 'tenants'));
    }

    public function upload(Request $request): RedirectResponse
    {
        set_time_limit(0);

        $data = $request->validate([
            'foto_struk' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:5120'],
            'id_tenant' => ['required', Rule::exists('tenant', 'id_tenant')],
            'tanggal_transaksi' => ['required', 'date'],
            'nominal_belanja' => ['required', 'numeric', 'min:1'],
        ]);

        $uploadDir = public_path('storage/struk');
        if (! is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $file = $data['foto_struk'];
        $fileName = uniqid('struk_', true) . '.' . $file->getClientOriginalExtension();
        $file->move($uploadDir, $fileName);
        $path = 'struk/' . $fileName;

        Transaksi::create([
            'id_pelanggan' => auth('pelanggan')->id(),
            'id_tenant' => $data['id_tenant'],
            'id_pegawai' => null,
            'tanggal_transaksi' => $data['tanggal_transaksi'],
            'nominal_belanja' => $data['nominal_belanja'],
            'poin_yang_didapat' => 0,
            'foto_struk' => $path,
            'status_transaksi' => 'Pending',
            'catatan_tolak' => null,
        ]);

        return back()->with('success', 'Struk berhasil dikirim. Status transaksi masuk antrean verifikasi staf.');
    }

    public function showReuploadForm(Transaksi $transaksi): View
    {
        $this->ensureReuploadAllowed($transaksi);

        $transaksi->load('tenant:id_tenant,nama_tenant');

        return view('customer.reupload-struk', compact('transaksi'));
    }

    public function reupload(Request $request, Transaksi $transaksi): RedirectResponse
    {
        set_time_limit(0);
        $this->ensureReuploadAllowed($transaksi);

        $data = $request->validate([
            'foto_struk' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:5120'],
        ]);

        $uploadDir = public_path('storage/struk');
        if (! is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $file = $data['foto_struk'];
        $fileName = uniqid('struk_', true) . '.' . $file->getClientOriginalExtension();
        $file->move($uploadDir, $fileName);
        $oldPhoto = public_path('storage/' . $transaksi->foto_struk);

        $transaksi->update([
            'id_pegawai' => null,
            'foto_struk' => 'struk/' . $fileName,
            'status_transaksi' => 'Pending',
            'catatan_tolak' => null,
        ]);

        if (File::exists($oldPhoto)) {
            File::delete($oldPhoto);
        }

        return redirect()
            ->route('pelanggan.upload-struk')
            ->with('success', 'Foto struk TRX-' . $transaksi->id_transaksi . ' berhasil diperbarui dan dikirim ulang untuk verifikasi.');
    }

    private function ensureReuploadAllowed(Transaksi $transaksi): void
    {
        abort_unless((int) $transaksi->id_pelanggan === (int) auth('pelanggan')->id(), 404);
        abort_unless($transaksi->status_transaksi === 'Rejected', 422, 'Hanya struk berstatus Rejected yang dapat diunggah ulang.');

        $hoursSinceUpload = $transaksi->created_at->diffInHours(now());
        abort_if($hoursSinceUpload >= 72, 422, 'Masa berlaku unggah ulang struk telah kedaluwarsa.');
    }
}
