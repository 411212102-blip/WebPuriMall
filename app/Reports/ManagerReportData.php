<?php

namespace App\Reports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ManagerReportData
{
    public static function tenantPerformance(): Collection
    {
        return DB::table('tenant')
            ->leftJoin('kategori_tenant', 'tenant.id_kategori', '=', 'kategori_tenant.id_kategori')
            ->leftJoin('transaksi', function ($join) {
                $join->on('tenant.id_tenant', '=', 'transaksi.id_tenant')
                    ->where('transaksi.status_transaksi', '=', 'Approved');
            })
            ->select([
                'tenant.id_tenant',
                'tenant.nama_tenant',
                DB::raw("COALESCE(kategori_tenant.nama_kategori, '-') as nama_kategori"),
                DB::raw('COUNT(transaksi.id_transaksi) as total_struk'),
                DB::raw('COALESCE(SUM(transaksi.nominal_belanja), 0) as total_omzet'),
                DB::raw('COALESCE(SUM(transaksi.poin_yang_didapat), 0) as total_poin'),
                'tenant.is_active',
            ])
            ->groupBy('tenant.id_tenant', 'tenant.nama_tenant', 'kategori_tenant.nama_kategori', 'tenant.is_active')
            ->orderByDesc('total_omzet')
            ->orderBy('tenant.nama_tenant')
            ->get();
    }

    public static function memberLoyalty(): Collection
    {
        $redeemed = DB::table('penukaran_poin')
            ->select('id_pelanggan', DB::raw('COALESCE(SUM(poin_terpotong), 0) as redeemed_points'))
            ->groupBy('id_pelanggan');

        return DB::table('pelanggan')
            ->leftJoin('cluster', 'pelanggan.id_cluster', '=', 'cluster.id_cluster')
            ->leftJoinSub($redeemed, 'redeem', function ($join) {
                $join->on('pelanggan.id_pelanggan', '=', 'redeem.id_pelanggan');
            })
            ->select([
                'pelanggan.id_pelanggan',
                'pelanggan.no_pelanggan',
                'pelanggan.nama_pelanggan',
                'pelanggan.email_pelanggan',
                'pelanggan.no_whatsapp_pelanggan',
                DB::raw('COALESCE(redeem.redeemed_points, 0) as redeemed_points'),
                DB::raw('pelanggan.total_poin as current_points'),
                DB::raw('(pelanggan.total_poin + COALESCE(redeem.redeemed_points, 0)) as lifetime_points'),
                DB::raw("COALESCE(cluster.nama_cluster, 'Belum Diclassifikasi') as nama_cluster"),
                'pelanggan.tgl_daftar',
            ])
            ->orderByDesc('lifetime_points')
            ->orderBy('pelanggan.nama_pelanggan')
            ->get();
    }
}
