<?php

namespace App\Http\Controllers;

use App\Models\PenukaranPoin;
use Illuminate\View\View;

class MyRewardController extends Controller
{
    public function index(): View
    {
        $pelanggan = auth('pelanggan')->user();

        $vouchers = PenukaranPoin::with('hadiah:id_hadiah,nama_hadiah,poin_dibutuhkan,gambar_url')
            ->where('id_pelanggan', $pelanggan->id_pelanggan)
            ->orderByDesc('tanggal_redeem')
            ->get();

        return view('customer.my-rewards', compact('pelanggan', 'vouchers'));
    }
}
