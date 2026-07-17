@extends('layouts.app')

@section('title', 'Hadiah Saya')

@section('content')
    <section class="rounded-lg border border-gold/40 bg-white/85 p-6 shadow-premium">
        <p class="text-sm font-bold uppercase tracking-[0.18em] text-gold">My Rewards</p>
        <h2 class="mt-2 text-3xl font-black text-deepRed">Hadiah Saya</h2>
        <p class="mt-3 max-w-2xl text-zinc-700">
            Tunjukkan QR voucher ini kepada petugas Customer Service untuk mengambil hadiah fisik.
        </p>

        <div class="mt-7 grid gap-5 md:grid-cols-2 xl:grid-cols-3">
            @forelse ($vouchers as $voucher)
                @php
                    $code = $voucher->voucher_code ?: 'PIM-' . $voucher->id_redeem . '-' . optional($voucher->tanggal_redeem)->format('YmdHis');
                    $hash = str_pad(substr(md5($code), 0, 64), 64, '0');
                    $isClaimed = in_array($voucher->status_redeem, ['Claimed', 'Used'], true);
                @endphp

                <article class="overflow-hidden rounded-2xl border border-gold/40 bg-cream shadow-premium">
                    <div class="bg-deepRed px-5 py-4 text-cream">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="text-xs font-black uppercase tracking-[0.22em] text-gold">E-Voucher</p>
                                <h3 class="mt-1 text-xl font-black">{{ $voucher->hadiah?->nama_hadiah ?? 'Hadiah' }}</h3>
                            </div>
                            <span class="rounded-full px-3 py-1 text-xs font-black {{ $isClaimed ? 'bg-zinc-200 text-zinc-700' : 'bg-gold text-deepRed' }}">
                                {{ $voucher->status_redeem ?? 'Success' }}
                            </span>
                        </div>
                    </div>

                    <div class="grid gap-5 p-5 sm:grid-cols-[1fr_auto] sm:items-center">
                        <div>
                            <p class="text-sm font-semibold text-zinc-600">Kode Voucher</p>
                            <p class="mt-2 break-all rounded-lg border border-gold/40 bg-white px-3 py-2 font-mono text-sm font-black text-deepRed">
                                {{ $code }}
                            </p>
                            <p class="mt-4 text-sm text-zinc-700">
                                Redeem: {{ $voucher->tanggal_redeem?->format('d M Y H:i') }}<br>
                                Poin terpotong: <strong>{{ number_format($voucher->poin_terpotong, 0, ',', '.') }}</strong>
                            </p>
                            @if ($voucher->claimed_at)
                                <p class="mt-3 text-sm font-bold text-zinc-700">
                                    Diklaim: {{ $voucher->claimed_at->format('d M Y H:i') }}
                                </p>
                            @endif
                        </div>

                        <div class="mx-auto grid h-36 w-36 grid-cols-8 gap-1 rounded-xl border-4 border-white bg-white p-2 shadow">
                            @foreach (str_split($hash) as $char)
                                <span class="{{ hexdec($char) % 2 === 0 ? 'bg-deepRed' : 'bg-cream' }} rounded-[2px]"></span>
                            @endforeach
                        </div>
                    </div>
                </article>
            @empty
                <div class="rounded-lg border border-gold/40 bg-cream p-6 text-zinc-700">
                    Belum ada hadiah yang ditukarkan.
                </div>
            @endforelse
        </div>
    </section>
@endsection
