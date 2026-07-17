<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fasilitas extends Model
{
    protected $table = 'fasilitas';
    protected $primaryKey = 'id_fasilitas';

    protected $fillable = [
        'nama_fasilitas',
        'lokasi_lantai',
        'deskripsi',
    ];
}
