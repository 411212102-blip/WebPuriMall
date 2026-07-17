<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function show(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::guard('pelanggan')->attempt([
            'email_pelanggan' => $data['email'],
            'password' => $data['password'],
            'is_active' => 1,
        ], false)) {
            $request->session()->regenerate();

            return redirect()->intended('/pelanggan/dashboard');
        }

        if (Auth::guard('pegawai')->attempt([
            'email_pegawai' => $data['email'],
            'password' => $data['password'],
            'is_active' => 1,
        ], false)) {
            $request->session()->regenerate();

            return redirect()->intended($this->pegawaiRedirectPath(Auth::guard('pegawai')->user()->role));
        }

        return back()
            ->withErrors(['email' => 'Email atau password tidak sesuai.'])
            ->onlyInput('email');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('pelanggan')->logout();
        Auth::guard('pegawai')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    private function pegawaiRedirectPath(string $role): string
    {
        return match ($role) {
            'manager' => '/mgmt/dashboard',
            'superadmin', 'admin', 'kasir' => '/staff/dashboard',
            default => '/',
        };
    }
}
