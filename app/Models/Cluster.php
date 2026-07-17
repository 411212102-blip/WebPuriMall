<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cluster extends Model
{
    protected $table = 'cluster';
    protected $primaryKey = 'id_cluster';

    protected $fillable = [
        'nama_cluster',
        'deskripsi',
    ];
}
