@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
    <section class="mx-auto max-w-4xl rounded-lg border border-gold/40 bg-white p-6 shadow-premium sm:p-8">
        <p class="text-sm font-black uppercase tracking-[0.18em] text-gold">Customer Profile</p>
        <h2 class="mt-2 text-3xl font-black text-deepRed">Profil Saya</h2>
        <p class="mt-3 text-sm leading-6 text-zinc-700">Perbarui identitas member agar layanan dan notifikasi akun tetap akurat.</p>

        <div class="mt-6 rounded-lg border border-gold/30 bg-cream p-4">
            <p class="text-xs font-black uppercase tracking-wider text-gold">Nomor Pelanggan</p>
            <p class="mt-1 text-xl font-black text-deepRed">{{ $pelanggan->no_pelanggan ?? '-' }}</p>
        </div>

        <form method="POST" action="{{ route('pelanggan.profile.update') }}" class="mt-7 grid gap-5 md:grid-cols-2">
            @csrf
            @method('PUT')

            <div class="md:col-span-2">
                <label class="mb-2 block text-sm font-black text-deepRed">Nama Lengkap</label>
                <input name="nama_pelanggan" value="{{ old('nama_pelanggan', $pelanggan->nama_pelanggan) }}" required maxlength="150" class="w-full rounded-md border border-zinc-300 px-4 py-3 outline-none focus:border-gold focus:ring-2 focus:ring-gold/30">
                @error('nama_pelanggan') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-2">
                <label class="mb-2 block text-sm font-black text-deepRed">Alamat Lengkap</label>
                <textarea name="alamat" rows="3" required maxlength="255" class="w-full rounded-md border border-zinc-300 px-4 py-3 outline-none focus:border-gold focus:ring-2 focus:ring-gold/30">{{ old('alamat', $pelanggan->alamat) }}</textarea>
                @error('alamat') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="mb-2 block text-sm font-black text-deepRed">Email</label>
                <input type="email" name="email_pelanggan" value="{{ old('email_pelanggan', $pelanggan->email_pelanggan) }}" required maxlength="150" class="w-full rounded-md border border-zinc-300 px-4 py-3 outline-none focus:border-gold focus:ring-2 focus:ring-gold/30">
                @error('email_pelanggan') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="mb-2 block text-sm font-black text-deepRed">No. WhatsApp</label>
                <input name="no_whatsapp_pelanggan" value="{{ old('no_whatsapp_pelanggan', $pelanggan->no_whatsapp_pelanggan) }}" required maxlength="15" class="w-full rounded-md border border-zinc-300 px-4 py-3 outline-none focus:border-gold focus:ring-2 focus:ring-gold/30">
                @error('no_whatsapp_pelanggan') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-2">
                <label class="mb-2 block text-sm font-black text-deepRed">NIK KTP</label>
                <input name="no_ktp_pelanggan" value="{{ old('no_ktp_pelanggan', $pelanggan->no_ktp_pelanggan) }}" required maxlength="16" inputmode="numeric" class="w-full rounded-md border border-zinc-300 px-4 py-3 outline-none focus:border-gold focus:ring-2 focus:ring-gold/30">
                @error('no_ktp_pelanggan') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-2">
                <button class="rounded-md bg-deepRed px-5 py-3 font-black text-cream transition hover:bg-gold hover:text-deepRed">Simpan Perubahan</button>
            </div>
        </form>
    </section>
@endsection
