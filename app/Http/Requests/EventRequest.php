<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('pegawai')->check();
    }

    public function rules(): array
    {
        return [
            'nama_event' => ['required', 'string', 'max:200'],
            'gambar_event' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'lokasi' => ['required', Rule::in(['Outdoor', 'Indoor Center Court 1', 'Indoor Center Court 2', 'Indoor Court 3'])],
            'tgl_mulai' => ['required', 'date'],
            'tgl_selesai' => ['required', 'date', 'after_or_equal:tgl_mulai'],
            'deskripsi' => ['nullable', 'string'],
            'is_active' => ['required', 'boolean'],
        ];
    }
}
