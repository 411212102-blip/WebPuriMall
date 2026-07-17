<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminCustomerController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search'));

        $pelanggan = Pelanggan::query()
            ->with('cluster:id_cluster,nama_cluster')
            ->when($search, function ($query) use ($search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('no_pelanggan', 'like', "%{$search}%")
                        ->orWhere('nama_pelanggan', 'like', "%{$search}%")
                        ->orWhere('email_pelanggan', 'like', "%{$search}%")
                        ->orWhere('no_whatsapp_pelanggan', 'like', "%{$search}%");
                });
            })
            ->latest('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('admin.pelanggan.index', compact('pelanggan', 'search'));
    }
}
