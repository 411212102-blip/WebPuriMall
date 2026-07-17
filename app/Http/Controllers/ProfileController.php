<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(): View
    {
        return view('customer.profile', [
            'pelanggan' => auth('pelanggan')->user(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        /** @var Pelanggan $pelanggan */
        $pelanggan = auth('pelanggan')->user();

        $data = $request->validate([
            'nama_pelanggan' => ['required', 'string', 'max:150'],
            'alamat' => ['required', 'string', 'max:255'],
            'no_whatsapp_pelanggan' => ['required', 'string', 'max:15'],
            'email_pelanggan' => [
                'required',
                'email',
                'max:150',
                Rule::unique('pelanggan', 'email_pelanggan')->ignore($pelanggan->id_pelanggan, 'id_pelanggan'),
            ],
            'no_ktp_pelanggan' => [
                'required',
                'digits:16',
                Rule::unique('pelanggan', 'no_ktp_pelanggan')->ignore($pelanggan->id_pelanggan, 'id_pelanggan'),
            ],
        ]);

        $pelanggan->update($data);

        return back()->with('success', 'Profil pelanggan berhasil diperbarui.');
    }
}
