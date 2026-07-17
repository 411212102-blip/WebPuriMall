@extends('layouts.app')

@section('title', 'Rewards')

@section('content')
    <section class="rounded-lg border border-gold/40 bg-white/80 p-6 shadow-premium">
        <div class="flex flex-wrap items-end justify-between gap-3">
            <div>
                <p class="text-sm font-bold uppercase tracking-[0.18em] text-gold">Rewards Catalog</p>
                <h2 class="mt-2 text-3xl font-black text-deepRed">Tukar Poin</h2>
            </div>
            <div class="rounded-full bg-deepRed px-5 py-3 font-black text-cream">
                {{ number_format($pelanggan->total_poin, 0, ',', '.') }} Poin
            </div>
        </div>

        @if (session('success'))
            <div class="mt-5 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm font-semibold text-green-800">{{ session('success') }}</div>
        @endif
        @error('redeem')
            <div class="mt-5 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-800">{{ $message }}</div>
        @enderror

        <div class="mt-6 grid gap-4 md:grid-cols-3 lg:grid-cols-4">
            @forelse ($hadiah as $item)
                <article class="rounded-lg border border-gold/30 bg-cream p-5">
                    <div class="grid aspect-[4/3] place-items-center rounded-md bg-white text-deepRed">
                        @if ($item->gambar_url)
                            <img src="{{ $item->gambar_url }}" alt="{{ $item->nama_hadiah }}" class="h-full w-full rounded-md object-cover">
                        @else
                            <span class="font-black">Reward</span>
                        @endif
                    </div>
                    <h3 class="mt-4 font-black text-deepRed">{{ $item->nama_hadiah }}</h3>
                    <p class="mt-2 text-sm font-bold text-zinc-700">{{ number_format($item->poin_dibutuhkan, 0, ',', '.') }} Poin</p>
                    <p class="mt-1 text-sm text-zinc-600">Stok: {{ $item->stok }}</p>
                    <form method="POST" action="{{ route('pelanggan.rewards.redeem') }}" class="mt-4">
                        @csrf
                        <input type="hidden" name="id_hadiah" value="{{ $item->id_hadiah }}">
                        <button @disabled($item->stok < 1 || $pelanggan->total_poin < $item->poin_dibutuhkan)
                                class="w-full rounded-md px-4 py-3 font-black {{ $item->stok < 1 || $pelanggan->total_poin < $item->poin_dibutuhkan ? 'bg-zinc-300 text-zinc-500' : 'bg-deepRed text-cream hover:bg-gold hover:text-deepRed' }}">
                            Tukar Poin
                        </button>
                    </form>
                </article>
            @empty
                <p class="text-zinc-600">Belum ada hadiah aktif.</p>
            @endforelse
        </div>
    </section>

    <section class="mt-7 rounded-lg border border-gold/40 bg-white/80 p-6 shadow-premium">
        <h3 class="text-2xl font-black text-deepRed">Riwayat Penukaran</h3>
        <div class="mt-5 overflow-x-auto">
            <table class="w-full min-w-[720px] text-left text-sm">
                <thead class="border-b border-gold/30 text-xs uppercase tracking-wide text-zinc-500">
                    <tr>
                        <th class="py-3 pr-4">Hadiah</th>
                        <th class="px-4 py-3">Tanggal</th>
                        <th class="px-4 py-3">Poin</th>
                        <th class="px-4 py-3">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gold/20">
                    @forelse ($riwayatRedeem as $redeem)
                        <tr>
                            <td class="py-4 pr-4 font-bold">{{ $redeem->hadiah?->nama_hadiah ?? '-' }}</td>
                            <td class="px-4 py-4">{{ $redeem->tanggal_redeem?->format('d M Y H:i') }}</td>
                            <td class="px-4 py-4">{{ number_format($redeem->poin_terpotong, 0, ',', '.') }}</td>
                            <td class="px-4 py-4"><span class="rounded-full bg-green-100 px-3 py-1 text-xs font-black text-green-800">{{ $redeem->status_redeem ?? 'Success' }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="py-8 text-center text-zinc-500">Belum ada penukaran reward.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
