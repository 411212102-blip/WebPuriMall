<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FasilitasRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('pegawai')->check();
    }

    public function rules(): array
    {
        return [
            'nama_fasilitas' => ['required', 'string', 'max:150'],
            'lokasi_lantai' => ['required', 'string', 'max:50'],
            'deskripsi' => ['nullable', 'string'],
        ];
    }
}
