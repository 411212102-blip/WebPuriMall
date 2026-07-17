<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenukaranPoin extends Model
{
    protected $table = 'penukaran_poin';
    protected $primaryKey = 'id_redeem';
    public $timestamps = false;

    protected $fillable = [
        'id_pelanggan',
        'id_hadiah',
        'tanggal_redeem',
        'poin_terpotong',
        'status_redeem',
        'voucher_code',
        'claimed_at',
        'claimed_by',
    ];

    protected $casts = [
        'tanggal_redeem' => 'datetime',
        'claimed_at' => 'datetime',
    ];

    public function hadiah()
    {
        return $this->belongsTo(KatalogHadiah::class, 'id_hadiah', 'id_hadiah');
    }

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan', 'id_pelanggan');
    }

    public function claimedBy()
    {
        return $this->belongsTo(Pegawai::class, 'claimed_by', 'id_pegawai');
    }
}
