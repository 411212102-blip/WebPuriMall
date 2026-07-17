<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable([
    'nama_pegawai',
    'jabatan',
    'role',
    'shift',
    'email_pegawai',
    'password',
    'is_active',
])]
#[Hidden(['password'])]
class Pegawai extends Authenticatable
{
    use Notifiable;

    protected $table = 'pegawai';
    protected $primaryKey = 'id_pegawai';

    public function getAuthPasswordName(): string
    {
        return 'password';
    }

    public function getEmailAttribute(): string
    {
        return $this->email_pegawai;
    }

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }
}
