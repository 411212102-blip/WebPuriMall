@extends('layouts.app')

@section('title', 'Unggah Ulang Struk')

@section('content')
    <section class="mx-auto max-w-3xl rounded-lg border border-red-200 bg-white p-6 shadow-premium sm:p-8">
        <p class="text-sm font-black uppercase tracking-[0.18em] text-red-700">Perbaikan Struk Ditolak</p>
        <h2 class="mt-2 text-3xl font-black text-deepRed">Unggah Ulang TRX-{{ $transaksi->id_transaksi }}</h2>
        <p class="mt-3 text-sm leading-6 text-zinc-700">Data transaksi dikunci. Unggah foto pengganti yang lebih jelas sebelum batas waktu 72 jam berakhir.</p>

        <div class="mt-6 grid gap-3 rounded-lg border border-gold/30 bg-cream p-5 text-sm text-zinc-800 sm:grid-cols-2">
            <div><p class="font-bold text-deepRed">Nomor Struk</p><p class="mt-1">TRX-{{ $transaksi->id_transaksi }}</p></div>
            <div><p class="font-bold text-deepRed">Tenant</p><p class="mt-1">{{ $transaksi->tenant?->nama_tenant ?? '-' }}</p></div>
            <div><p class="font-bold text-deepRed">Tanggal Belanja</p><p class="mt-1">{{ $transaksi->tanggal_transaksi?->format('d M Y H:i') }}</p></div>
            <div><p class="font-bold text-deepRed">Nominal</p><p class="mt-1">Rp {{ number_format((float) $transaksi->nominal_belanja, 0, ',', '.') }}</p></div>
        </div>

        <div class="mt-5 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-800">
            <p class="font-black">Alasan Penolakan</p>
            <p class="mt-1">{{ $transaksi->catatan_tolak }}</p>
        </div>

        <form method="POST" action="{{ route('pelanggan.upload-struk.reupload.store', $transaksi->id_transaksi) }}" enctype="multipart/form-data" class="mt-6">
            @csrf
            <label class="block text-sm font-black text-deepRed">Foto Struk Pengganti</label>
            <input type="file" name="foto_struk" accept="image/png,image/jpeg" required class="mt-3 w-full rounded-md border border-zinc-300 bg-white px-4 py-3 text-sm outline-none focus:border-gold focus:ring-2 focus:ring-gold/30">
            @error('foto_struk') <p class="mt-2 text-sm font-semibold text-red-700">{{ $message }}</p> @enderror

            <div class="mt-6 flex flex-wrap gap-3">
                <button class="rounded-md bg-deepRed px-5 py-3 font-black text-cream hover:bg-gold hover:text-deepRed">Kirim Foto Pengganti</button>
                <a href="{{ route('pelanggan.dashboard') }}" class="rounded-md border border-gold px-5 py-3 font-black text-deepRed hover:bg-gold">Kembali</a>
            </div>
        </form>
    </section>
@endsection
