<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function show(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nama_pelanggan' => ['required', 'string', 'max:150'],
            'alamat_pelanggan' => ['required', 'string', 'max:255'],
            'email_pelanggan' => ['required', 'email', 'max:150', Rule::unique('pelanggan', 'email_pelanggan')],
            'no_whatsapp_pelanggan' => ['required', 'string', 'max:15'],
            'no_ktp_pelanggan' => ['required', 'digits:16', Rule::unique('pelanggan', 'no_ktp_pelanggan')],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $pelanggan = Pelanggan::create([
            'nama_pelanggan' => $data['nama_pelanggan'],
            'alamat' => $data['alamat_pelanggan'],
            'email_pelanggan' => $data['email_pelanggan'],
            'no_whatsapp_pelanggan' => $data['no_whatsapp_pelanggan'],
            'no_ktp_pelanggan' => $data['no_ktp_pelanggan'],
            'password' => Hash::make($data['password']),
            'tgl_daftar' => now()->toDateString(),
            'total_poin' => 0,
            'is_active' => 1,
        ]);

        $pelanggan->update([
            'no_pelanggan' => sprintf('PIM-%s-%04d', now()->format('Y'), $pelanggan->id_pelanggan),
        ]);

        Auth::guard('pelanggan')->login($pelanggan);
        $request->session()->regenerate();

        return redirect('/pelanggan/dashboard');
    }
}
