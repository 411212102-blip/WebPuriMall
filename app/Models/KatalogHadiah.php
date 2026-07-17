<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KatalogHadiah extends Model
{
    protected $table = 'katalog_hadiah';
    protected $primaryKey = 'id_hadiah';

    protected $fillable = [
        'nama_hadiah',
        'poin_dibutuhkan',
        'stok',
        'gambar_url',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
