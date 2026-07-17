@extends('layouts.app')

@section('title', 'Upload Struk Belanja')

@section('content')
    <section class="grid gap-6 lg:grid-cols-[0.9fr_1.1fr]">
        <div class="rounded-lg border border-gold/40 bg-white/80 p-6 shadow-premium">
            <p class="text-sm font-bold uppercase tracking-[0.18em] text-gold">Panduan Upload Struk</p>
            <h2 class="mt-2 text-3xl font-black text-deepRed">Pastikan Struk Terbaca Jelas</h2>
            <p class="mt-4 text-zinc-700">
                Foto yang rapi membantu Agent 1: OCR Extraction Engine membaca nama tenant, tanggal transaksi,
                dan nominal belanja dengan akurat. Struk yang tidak jelas dapat masuk antrean penolakan staf.
            </p>

            <div class="mt-6 space-y-4 text-sm leading-6 text-zinc-700">
                <div class="rounded-lg bg-cream p-4">
                    <h3 class="font-black text-deepRed">Kriteria Foto Ideal</h3>
                    <ul class="mt-2 list-disc space-y-1 pl-5">
                        <li>Gunakan pencahayaan terang dan merata, tanpa bayangan tangan atau pantulan flash.</li>
                        <li>Letakkan struk tegak, rata, tidak terlipat, dan seluruh bagian struk masuk frame.</li>
                        <li>Pastikan nama tenant, tanggal transaksi, dan nominal pembayaran terlihat tajam.</li>
                        <li>Ambil foto dari arah atas, bukan miring ekstrem, agar teks tidak terdistorsi.</li>
                        <li>Gunakan file JPG atau PNG dengan kualitas asli, bukan screenshot dari chat.</li>
                    </ul>
                </div>

                <div class="rounded-lg border border-red-200 bg-red-50 p-4">
                    <h3 class="font-black text-deepRed">Validasi & Pencegahan Fraud</h3>
                    <p class="mt-2">
                        Satu struk hanya boleh diklaim satu kali. Staf akan memverifikasi keaslian fisik struk,
                        kecocokan tenant, tanggal, nominal, dan potensi manipulasi gambar sebelum poin disetujui.
                    </p>
                </div>

                <div class="rounded-lg border border-gold/50 bg-white p-4">
                    <h3 class="font-black text-deepRed">Status Setelah Upload</h3>
                    <p class="mt-2">
                        Setelah dikirim, transaksi masuk status <strong>Pending</strong>. Poin baru masuk ke akun
                        setelah staf menekan <strong>Approve</strong>. Jika gambar blur, terpotong, atau data tidak cocok,
                        status dapat berubah menjadi <strong>Rejected</strong> disertai catatan penolakan.
                    </p>
                </div>
            </div>
        </div>

        <form class="rounded-lg border border-gold/40 bg-white/80 p-6 shadow-premium"
              method="POST"
              action="{{ route('pelanggan.upload-struk.store') }}"
              enctype="multipart/form-data">
            @csrf
            @if (session('success'))
                <div class="mb-5 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm font-semibold text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            <label class="block text-sm font-black text-deepRed">Foto Struk</label>
            <input type="file" name="foto_struk" accept="image/png,image/jpeg"
                   required
                   class="mt-3 w-full rounded-md border border-zinc-300 bg-white px-4 py-3 text-sm outline-none focus:border-gold focus:ring-2 focus:ring-gold/30">
            @error('foto_struk')
                <p class="mt-2 text-sm font-semibold text-red-700">{{ $message }}</p>
            @enderror

            <div class="mt-5 grid gap-4 md:grid-cols-2">
                <div>
                    <label class="block text-sm font-black text-deepRed">Tenant</label>
                    <select name="id_tenant" required
                            class="mt-2 w-full rounded-md border border-zinc-300 bg-white px-4 py-3 text-sm outline-none focus:border-gold focus:ring-2 focus:ring-gold/30">
                        <option value="">Pilih tenant</option>
                        @foreach ($tenants as $tenant)
                            <option value="{{ $tenant->id_tenant }}" @selected(old('id_tenant') == $tenant->id_tenant)>
                                {{ $tenant->nama_tenant }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_tenant')
                        <p class="mt-2 text-sm font-semibold text-red-700">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-black text-deepRed">Tanggal Transaksi</label>
                    <input type="datetime-local" name="tanggal_transaksi" value="{{ old('tanggal_transaksi') }}" required
                           class="mt-2 w-full rounded-md border border-zinc-300 bg-white px-4 py-3 text-sm outline-none focus:border-gold focus:ring-2 focus:ring-gold/30">
                    @error('tanggal_transaksi')
                        <p class="mt-2 text-sm font-semibold text-red-700">{{ $message }}</p>
                    @enderror
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-black text-deepRed">Nominal Belanja</label>
                    <input type="number" name="nominal_belanja" value="{{ old('nominal_belanja') }}" min="1" step="0.01" required
                           class="mt-2 w-full rounded-md border border-zinc-300 bg-white px-4 py-3 text-sm outline-none focus:border-gold focus:ring-2 focus:ring-gold/30">
                    @error('nominal_belanja')
                        <p class="mt-2 text-sm font-semibold text-red-700">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <button class="mt-6 w-full rounded-md bg-deepRed px-4 py-3 font-black text-cream transition hover:bg-gold hover:text-deepRed">
                Kirim Struk untuk Verifikasi
            </button>
        </form>
    </section>

    <section class="mt-7 rounded-lg border border-gold/40 bg-white/80 p-6 shadow-premium">
        <div class="flex flex-wrap items-end justify-between gap-3">
            <div>
                <p class="text-sm font-bold uppercase tracking-[0.18em] text-gold">Pemantauan Status</p>
                <h2 class="mt-2 text-2xl font-black text-deepRed">Riwayat Struk Saya</h2>
            </div>
            <p class="text-sm text-zinc-600">{{ $riwayatStruk->count() }} struk tercatat</p>
        </div>

        <div class="mt-5 overflow-x-auto">
            <table class="w-full min-w-[760px] text-left text-sm">
                <thead class="border-b border-gold/30 text-xs uppercase tracking-wide text-zinc-500">
                    <tr>
                        <th class="py-3 pr-4">ID</th>
                        <th class="px-4 py-3">Tenant</th>
                        <th class="px-4 py-3">Tanggal</th>
                        <th class="px-4 py-3">Nominal</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Catatan</th>
                        <th class="px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gold/20">
                    @forelse ($riwayatStruk as $trx)
                        @php
                            $badge = match ($trx->status_transaksi) {
                                'Approved' => 'bg-green-100 text-green-800 border-green-200',
                                'Rejected' => 'bg-red-100 text-red-800 border-red-200',
                                default => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                            };
                        @endphp
                        <tr>
                            <td class="py-4 pr-4 font-black text-deepRed">#{{ $trx->id_transaksi }}</td>
                            <td class="px-4 py-4">{{ $trx->tenant?->nama_tenant ?? '-' }}</td>
                            <td class="px-4 py-4">{{ $trx->tanggal_transaksi?->format('d M Y H:i') }}</td>
                            <td class="px-4 py-4 font-bold">Rp {{ number_format((float) $trx->nominal_belanja, 0, ',', '.') }}</td>
                            <td class="px-4 py-4">
                                <span class="rounded-full border px-3 py-1 text-xs font-black {{ $badge }}">
                                    {{ $trx->status_transaksi }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-zinc-600">{{ $trx->catatan_tolak ?? '-' }}</td>
                            <td class="px-4 py-4">
                                @if ($trx->status_transaksi === 'Rejected')
                                    @if ($trx->created_at->diffInHours(now()) < 72)
                                        <a href="{{ route('pelanggan.upload-struk.reupload', $trx->id_transaksi) }}" class="inline-flex rounded-md bg-deepRed px-3 py-2 text-xs font-black text-cream hover:bg-gold hover:text-deepRed">Unggah Ulang</a>
                                    @else
                                        <span class="text-xs font-black text-red-700">Kedaluwarsa</span>
                                    @endif
                                @else
                                    <span class="text-zinc-500">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-zinc-500">Belum ada struk yang diunggah.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
