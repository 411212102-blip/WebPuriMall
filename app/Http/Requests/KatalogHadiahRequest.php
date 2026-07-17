<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KatalogHadiahRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('pegawai')->check();
    }

    public function rules(): array
    {
        return [
            'nama_hadiah' => ['required', 'string', 'max:200'],
            'poin_dibutuhkan' => ['required', 'integer', 'min:1'],
            'stok' => ['required', 'integer', 'min:0', 'max:65535'],
            'gambar_url' => ['nullable', 'url', 'max:500'],
            'is_active' => ['required', 'boolean'],
        ];
    }
}
