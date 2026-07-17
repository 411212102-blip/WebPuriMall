<?php

namespace App\Http\Controllers;

use App\Models\PenukaranPoin;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class VoucherClaimController extends Controller
{
    public function index(): View
    {
        return view('admin.voucher-claims.index');
    }

    public function claim(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'voucher_code' => ['required', 'string', 'max:80'],
        ], [
            'voucher_code.required' => 'Masukkan kode voucher terlebih dahulu.',
        ]);

        try {
            $voucher = DB::transaction(function () use ($data) {
                $voucher = PenukaranPoin::with(['pelanggan:id_pelanggan,nama_pelanggan', 'hadiah:id_hadiah,nama_hadiah'])
                    ->where('voucher_code', trim($data['voucher_code']))
                    ->lockForUpdate()
                    ->first();

                if (! $voucher) {
                    throw new \RuntimeException('Kode voucher tidak ditemukan.');
                }

                if (in_array($voucher->status_redeem, ['Claimed', 'Used'], true)) {
                    throw new \RuntimeException('Voucher sudah tidak berlaku / sudah pernah digunakan.');
                }

                $voucher->update([
                    'status_redeem' => 'Claimed',
                    'claimed_at' => now(),
                    'claimed_by' => auth('pegawai')->id(),
                ]);

                return $voucher;
            });
        } catch (\RuntimeException $exception) {
            return back()->withErrors(['voucher_code' => $exception->getMessage()])->withInput();
        }

        return back()->with('success', 'Voucher Valid. Silakan serahkan hadiah fisik kepada pelanggan.');
    }
}
