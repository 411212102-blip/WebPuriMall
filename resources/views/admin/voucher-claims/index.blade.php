@extends('layouts.admin')

@section('title', 'Klaim Voucher Hadiah')
@section('page-title', 'Klaim Voucher Hadiah')

@section('content')
    <section class="mx-auto max-w-3xl rounded-xl border border-gold/30 bg-white p-6 shadow-glow">
        <div class="flex items-center gap-4">
            <div class="grid h-14 w-14 place-items-center rounded-2xl bg-deepRed text-gold">
                <i data-lucide="ticket-check" class="h-7 w-7"></i>
            </div>
            <div>
                <p class="text-sm font-black uppercase tracking-[0.2em] text-gold">Customer Service</p>
                <h3 class="text-2xl font-black text-deepRed">Validasi Voucher Fisik</h3>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.voucher-claims.claim') }}" class="mt-7 grid gap-4">
            @csrf
            <div>
                <label class="mb-2 block text-sm font-black text-gray-800">Masukkan Kode Voucher</label>
                <input name="voucher_code" value="{{ old('voucher_code') }}" required maxlength="80"
                       placeholder="Contoh: PIM-20260529103000-1-2"
                       class="w-full rounded-xl border border-gold/50 bg-cream px-4 py-3 font-mono text-gray-900 outline-none focus:border-deepRed">
            </div>

            <div class="flex flex-col gap-3 sm:flex-row">
                <button class="inline-flex items-center justify-center gap-2 rounded-xl bg-deepRed px-5 py-3 font-black text-cream hover:bg-gold hover:text-deepRed">
                    <i data-lucide="shield-check" class="h-5 w-5"></i>
                    Validasi & Serahkan Hadiah
                </button>
                <button type="button" onclick="document.querySelector('[name=voucher_code]').focus()"
                        class="inline-flex items-center justify-center gap-2 rounded-xl border border-gold px-5 py-3 font-black text-deepRed hover:bg-gold">
                    <i data-lucide="scan-line" class="h-5 w-5"></i>
                    Scan QR Code
                </button>
            </div>
        </form>
    </section>
@endsection
