<?php

namespace App\Http\Controllers;

use App\Models\PenukaranPoin;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RedeemHistoryController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));

        $redeems = PenukaranPoin::query()
            ->with([
                'pelanggan:id_pelanggan,nama_pelanggan,email_pelanggan',
                'hadiah:id_hadiah,nama_hadiah',
                'claimedBy:id_pegawai,nama_pegawai',
            ])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('voucher_code', 'like', "%{$search}%")
                        ->orWhereHas('pelanggan', fn ($q) => $q->where('nama_pelanggan', 'like', "%{$search}%"))
                        ->orWhereHas('hadiah', fn ($q) => $q->where('nama_hadiah', 'like', "%{$search}%"));
                });
            })
            ->orderByDesc('tanggal_redeem')
            ->paginate(15)
            ->withQueryString();

        return view('admin.redeem-history.index', compact('redeems', 'search'));
    }
}
