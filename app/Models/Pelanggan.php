<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable([
    'id_cluster',
    'no_pelanggan',
    'nama_pelanggan',
    'alamat',
    'no_ktp_pelanggan',
    'no_whatsapp_pelanggan',
    'email_pelanggan',
    'password',
    'total_poin',
    'tgl_daftar',
    'is_active',
])]
#[Hidden(['password'])]
class Pelanggan extends Authenticatable
{
    use Notifiable;

    protected $table = 'pelanggan';
    protected $primaryKey = 'id_pelanggan';

    public function getAuthPasswordName(): string
    {
        return 'password';
    }

    public function getEmailAttribute(): string
    {
        return $this->email_pelanggan;
    }

    public function cluster()
    {
        return $this->belongsTo(Cluster::class, 'id_cluster', 'id_cluster');
    }

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'id_pelanggan', 'id_pelanggan');
    }

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'tgl_daftar' => 'date',
            'is_active' => 'boolean',
        ];
    }
}
