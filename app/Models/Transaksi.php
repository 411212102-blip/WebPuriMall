<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transaksi';
    protected $primaryKey = 'id_transaksi';

    protected $fillable = [
        'id_pelanggan',
        'id_tenant',
        'id_pegawai',
        'tanggal_transaksi',
        'nominal_belanja',
        'poin_yang_didapat',
        'foto_struk',
        'status_transaksi',
        'catatan_tolak',
    ];

    protected $casts = [
        'tanggal_transaksi' => 'datetime',
        'nominal_belanja' => 'decimal:2',
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan', 'id_pelanggan');
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'id_tenant', 'id_tenant');
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'id_pegawai', 'id_pegawai');
    }
}
