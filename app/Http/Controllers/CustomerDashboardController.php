<?php

namespace App\Http\Controllers;

use App\Models\KatalogHadiah;
use App\Models\Transaksi;
use Illuminate\View\View;

class CustomerDashboardController extends Controller
{
    public function index(): View
    {
        $pelanggan = auth('pelanggan')->user();

        $transaksi = Transaksi::with('tenant:id_tenant,nama_tenant')
            ->where('id_pelanggan', $pelanggan->id_pelanggan)
            ->latest('tanggal_transaksi')
            ->get();

        $summary = [
            'pending' => $transaksi->where('status_transaksi', 'Pending')->count(),
            'approved' => $transaksi->where('status_transaksi', 'Approved')->count(),
            'rewards' => KatalogHadiah::where('is_active', 1)->where('stok', '>', 0)->count(),
        ];

        $rejectedTransaksi = $transaksi->where('status_transaksi', 'Rejected');

        return view('customer.dashboard', compact('pelanggan', 'transaksi', 'summary', 'rejectedTransaksi'));
    }
}
