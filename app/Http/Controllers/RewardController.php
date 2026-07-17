<?php

namespace App\Http\Controllers;

use App\Models\KatalogHadiah;
use App\Models\PenukaranPoin;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class RewardController extends Controller
{
    public function index(): View
    {
        $pelanggan = auth('pelanggan')->user();
        $hadiah = KatalogHadiah::where('is_active', 1)
            ->orderBy('poin_dibutuhkan')
            ->get();

        $riwayatRedeem = PenukaranPoin::with('hadiah:id_hadiah,nama_hadiah')
            ->where('id_pelanggan', $pelanggan->id_pelanggan)
            ->orderByDesc('tanggal_redeem')
            ->get();

        return view('customer.rewards', compact('pelanggan', 'hadiah', 'riwayatRedeem'));
    }

    public function redeem(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'id_hadiah' => ['required', 'integer', 'exists:katalog_hadiah,id_hadiah'],
        ], [
            'id_hadiah.required' => 'Hadiah yang dipilih tidak valid.',
            'id_hadiah.exists' => 'Hadiah yang dipilih tidak ditemukan.',
        ]);

        $pelanggan = auth('pelanggan')->user();

        try {
            DB::transaction(function () use ($pelanggan, $data) {
                $lockedHadiah = KatalogHadiah::whereKey($data['id_hadiah'])->lockForUpdate()->firstOrFail();
                $lockedPelanggan = $pelanggan->newQuery()->whereKey($pelanggan->id_pelanggan)->lockForUpdate()->firstOrFail();

                if ($lockedHadiah->stok < 1) {
                    throw new \RuntimeException('Stok hadiah sudah habis.');
                }

                if ($lockedPelanggan->total_poin < $lockedHadiah->poin_dibutuhkan) {
                    throw new \RuntimeException('Poin Anda belum cukup untuk menukar hadiah ini.');
                }

                $lockedPelanggan->decrement('total_poin', $lockedHadiah->poin_dibutuhkan);
                $lockedHadiah->decrement('stok');

                $redeemData = [
                    'id_pelanggan' => $lockedPelanggan->id_pelanggan,
                    'id_hadiah' => $lockedHadiah->id_hadiah,
                    'tanggal_redeem' => now(),
                    'poin_terpotong' => $lockedHadiah->poin_dibutuhkan,
                ];

                if (Schema::hasColumn('penukaran_poin', 'status_redeem')) {
                    $redeemData['status_redeem'] = 'Success';
                }

                if (Schema::hasColumn('penukaran_poin', 'voucher_code')) {
                    $redeemData['voucher_code'] = 'PIM-' . now()->format('YmdHis') . '-' . $lockedPelanggan->id_pelanggan . '-' . $lockedHadiah->id_hadiah;
                }

                PenukaranPoin::create($redeemData);
            });
        } catch (\RuntimeException $exception) {
            return back()->withErrors(['redeem' => $exception->getMessage()]);
        }

        return back()->with('success', 'Penukaran hadiah berhasil dicatat.');
    }
}
