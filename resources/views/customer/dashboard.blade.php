@extends('layouts.app')

@section('title', 'Dashboard Pelanggan')

@section('content')
    <section class="rounded-lg border border-gold/40 bg-white/75 p-6 shadow-premium">
        <p class="text-sm font-semibold uppercase tracking-[0.18em] text-gold">Customer Area</p>
        <h2 class="mt-2 text-3xl font-bold text-deepRed">Dashboard Pelanggan</h2>
        <p class="mt-3 max-w-2xl text-zinc-700">
            Selamat datang, {{ $pelanggan->nama_pelanggan }}. Pantau total poin, transaksi struk, dan peluang reward Anda.
        </p>
        <p class="mt-2 text-sm font-black text-deepRed">No. Pelanggan: {{ $pelanggan->no_pelanggan ?? '-' }}</p>
        <p class="mt-1 text-sm text-zinc-700">Alamat: {{ $pelanggan->alamat ?? 'Belum diisi' }}</p>

        <div class="mt-7 grid gap-4 md:grid-cols-4">
            <div class="rounded-lg border border-gold/30 bg-cream p-5">
                <p class="text-sm font-semibold text-zinc-600">Total Poin</p>
                <p class="mt-2 text-3xl font-black text-deepRed">{{ number_format($pelanggan->total_poin, 0, ',', '.') }}</p>
            </div>
            <div class="rounded-lg border border-gold/30 bg-cream p-5">
                <p class="text-sm font-semibold text-zinc-600">Pending</p>
                <p class="mt-2 text-3xl font-black text-deepRed">{{ $summary['pending'] }}</p>
            </div>
            <div class="rounded-lg border border-gold/30 bg-cream p-5">
                <p class="text-sm font-semibold text-zinc-600">Approved</p>
                <p class="mt-2 text-3xl font-black text-deepRed">{{ $summary['approved'] }}</p>
            </div>
            <div class="rounded-lg border border-gold/30 bg-cream p-5">
                <p class="text-sm font-semibold text-zinc-600">Reward Aktif</p>
                <p class="mt-2 text-3xl font-black text-deepRed">{{ $summary['rewards'] }}</p>
            </div>
        </div>

        <div class="mt-6 flex flex-wrap gap-3">
            <a href="{{ route('pelanggan.upload-struk') }}" class="rounded-md bg-deepRed px-5 py-3 font-black text-cream hover:bg-gold hover:text-deepRed">Upload Struk</a>
            <a href="{{ route('pelanggan.rewards.index') }}" class="rounded-md border border-gold px-5 py-3 font-black text-deepRed hover:bg-gold">Tukar Reward</a>
        </div>
    </section>

    @if ($rejectedTransaksi->isNotEmpty())
        <section class="mt-7 rounded-lg border border-red-200 bg-red-50 p-6 shadow-premium">
            <p class="text-sm font-bold uppercase tracking-[0.18em] text-red-700">Perlu Diperbaiki</p>
            <h3 class="mt-2 text-2xl font-black text-deepRed">Struk Ditolak</h3>
            <div class="mt-4 grid gap-3">
                @foreach ($rejectedTransaksi as $trx)
                    @php $canReupload = $trx->created_at->diffInHours(now()) < 72; @endphp
                    <div class="rounded-md border border-red-200 bg-white p-4 text-sm text-zinc-800">
                        <p class="font-black text-deepRed">TRX-{{ $trx->id_transaksi }} - {{ $trx->tenant?->nama_tenant ?? 'Tenant tidak tersedia' }}</p>
                        <p class="mt-1">{{ $trx->catatan_tolak ?? 'Silakan unggah ulang foto struk yang lebih jelas.' }}</p>
                        @if ($canReupload)
                            <a href="{{ route('pelanggan.upload-struk.reupload', $trx->id_transaksi) }}" class="mt-3 inline-flex rounded-md bg-deepRed px-4 py-2 font-black text-cream hover:bg-gold hover:text-deepRed">Unggah Ulang TRX-{{ $trx->id_transaksi }}</a>
                        @else
                            <p class="mt-3 font-black text-red-700">Masa berlaku unggah ulang struk telah kedaluwarsa.</p>
                        @endif
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    <section class="mt-7 rounded-lg border border-gold/40 bg-white/80 p-6 shadow-premium">
        <div class="flex flex-wrap items-end justify-between gap-3">
            <div>
                <p class="text-sm font-bold uppercase tracking-[0.18em] text-gold">Transaction History</p>
                <h3 class="mt-2 text-2xl font-black text-deepRed">Rincian Poin Per Struk</h3>
            </div>
            <p class="text-sm text-zinc-600">{{ $transaksi->count() }} transaksi</p>
        </div>

        <div class="mt-5 overflow-x-auto">
            <table class="w-full min-w-[900px] text-left text-sm">
                <thead class="border-b border-gold/30 text-xs uppercase tracking-wide text-zinc-500">
                    <tr>
                        <th class="py-3 pr-4">Nomor Struk</th>
                        <th class="px-4 py-3">Tenant</th>
                        <th class="px-4 py-3">Tanggal Belanja</th>
                        <th class="px-4 py-3">Nominal</th>
                        <th class="px-4 py-3">Poin</th>
                        <th class="px-4 py-3">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gold/20">
                    @forelse ($transaksi as $trx)
                        @php
                            $badge = match ($trx->status_transaksi) {
                                'Approved' => 'bg-green-100 text-green-800 border-green-200',
                                'Rejected' => 'bg-red-100 text-red-800 border-red-200',
                                default => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                            };
                        @endphp
                        <tr>
                            <td class="py-4 pr-4 font-black text-deepRed">TRX-{{ $trx->id_transaksi }}</td>
                            <td class="px-4 py-4">{{ $trx->tenant?->nama_tenant ?? '-' }}</td>
                            <td class="px-4 py-4">{{ $trx->tanggal_transaksi?->format('d M Y H:i') }}</td>
                            <td class="px-4 py-4 font-bold">Rp {{ number_format((float) $trx->nominal_belanja, 0, ',', '.') }}</td>
                            <td class="px-4 py-4 font-black text-deepRed">{{ number_format($trx->poin_yang_didapat, 0, ',', '.') }}</td>
                            <td class="px-4 py-4"><span class="rounded-full border px-3 py-1 text-xs font-black {{ $badge }}">{{ $trx->status_transaksi }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="py-8 text-center text-zinc-500">Belum ada transaksi struk.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
