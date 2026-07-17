<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    protected $table = 'tenant';
    protected $primaryKey = 'id_tenant';

    protected $fillable = [
        'id_kategori',
        'nama_tenant',
        'gambar_tenant',
        'no_unit',
        'lantai',
        'no_telp',
        'is_active',
    ];
}
