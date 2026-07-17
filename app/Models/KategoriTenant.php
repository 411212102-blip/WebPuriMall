<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriTenant extends Model
{
    protected $table = 'kategori_tenant';
    protected $primaryKey = 'id_kategori';

    protected $fillable = [
        'nama_kategori',
    ];
}
