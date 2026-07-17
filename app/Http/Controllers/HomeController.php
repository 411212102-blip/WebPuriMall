<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $pelanggan = auth('pelanggan')->user();

        $memberCluster = $pelanggan?->id_cluster
            ? DB::table('cluster')
                ->where('id_cluster', $pelanggan->id_cluster)
                ->first(['id_cluster', 'nama_cluster', 'deskripsi'])
            : null;

        $kategoriTenants = DB::table('kategori_tenant')
            ->orderBy('nama_kategori')
            ->get(['id_kategori', 'nama_kategori']);

        $tenants = DB::table('tenant')
            ->join('kategori_tenant', 'tenant.id_kategori', '=', 'kategori_tenant.id_kategori')
            ->where('tenant.is_active', 1)
            ->orderBy('kategori_tenant.nama_kategori')
            ->orderBy('tenant.nama_tenant')
            ->get([
                'tenant.id_tenant',
                'tenant.id_kategori',
                'tenant.nama_tenant',
                'tenant.gambar_tenant',
                'tenant.no_unit',
                'tenant.lantai',
                'kategori_tenant.nama_kategori',
            ]);

        $katalogHadiah = DB::table('katalog_hadiah')
            ->where('is_active', 1)
            ->orderBy('poin_dibutuhkan')
            ->limit(8)
            ->get([
                'id_hadiah',
                'nama_hadiah',
                'poin_dibutuhkan',
                'stok',
                'gambar_url',
            ]);

        $events = DB::table('event')
            ->where('is_active', 1)
            ->orderBy('tgl_mulai', 'desc')
            ->limit(6)
            ->get([
                'id_event',
                'nama_event',
                'gambar_event',
                'lokasi',
                'tgl_mulai',
                'tgl_selesai',
                'deskripsi',
            ]);

        $promos = Schema::hasTable('promo')
            ? DB::table('promo')
                ->orderByDesc('created_at')
                ->limit(6)
                ->get()
            : collect();

        $fasilitas = DB::table('fasilitas')
            ->orderBy('nama_fasilitas')
            ->get([
                'id_fasilitas',
                'nama_fasilitas',
                'lokasi_lantai',
                'deskripsi',
            ]);

        return view('welcome', compact(
            'pelanggan',
            'memberCluster',
            'kategoriTenants',
            'tenants',
            'katalogHadiah',
            'events',
            'promos',
            'fasilitas',
        ));
    }
}
