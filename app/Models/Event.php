<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $table = 'event';
    protected $primaryKey = 'id_event';

    protected $fillable = [
        'nama_event',
        'gambar_event',
        'lokasi',
        'tgl_mulai',
        'tgl_selesai',
        'deskripsi',
        'is_active',
    ];

    protected $casts = [
        'tgl_mulai' => 'date',
        'tgl_selesai' => 'date',
        'is_active' => 'boolean',
    ];
}
