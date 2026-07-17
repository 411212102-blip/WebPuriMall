@extends('layouts.admin')

@section('title', 'Riwayat Redeem')
@section('page-title', 'Riwayat Redeem Lengkap')

@section('content')
    <section class="rounded-xl border border-gold/30 bg-white shadow-glow">
        <div class="flex flex-col gap-4 border-b border-gold/30 px-5 py-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h3 class="text-lg font-black text-gray-900">Data Penukaran Poin</h3>
                <p class="text-sm text-gray-700">Pantau voucher Success dan Claimed dari seluruh pelanggan.</p>
            </div>
            <form method="GET" action="{{ route('admin.redeem-history.index') }}" class="flex gap-2">
                <input name="q" value="{{ $search }}" placeholder="Cari pelanggan/hadiah/kode"
                       class="w-72 rounded-xl border border-gold/50 bg-cream px-4 py-2 text-gray-900 outline-none focus:border-deepRed">
                <button class="rounded-xl bg-deepRed px-4 py-2 font-black text-cream hover:bg-gold hover:text-deepRed">Cari</button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[980px] text-left text-sm">
                <thead class="bg-cream text-xs uppercase tracking-wider text-gray-700">
                    <tr>
                        <th class="px-5 py-3">Nama Pelanggan</th>
                        <th class="px-5 py-3">Hadiah/Voucher</th>
                        <th class="px-5 py-3">Kode</th>
                        <th class="px-5 py-3">Poin</th>
                        <th class="px-5 py-3">Tanggal Redeem</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3">CS Klaim</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gold/30">
                    @forelse ($redeems as $redeem)
                        <tr class="hover:bg-cream">
                            <td class="px-5 py-4 font-bold text-gray-900">{{ $redeem->pelanggan?->nama_pelanggan ?? '-' }}</td>
                            <td class="px-5 py-4">{{ $redeem->hadiah?->nama_hadiah ?? '-' }}</td>
                            <td class="px-5 py-4 font-mono text-xs text-deepRed">{{ $redeem->voucher_code ?? '-' }}</td>
                            <td class="px-5 py-4">{{ number_format($redeem->poin_terpotong, 0, ',', '.') }}</td>
                            <td class="px-5 py-4">{{ $redeem->tanggal_redeem?->format('d M Y H:i') }}</td>
                            <td class="px-5 py-4">
                                <span class="rounded-full px-3 py-1 text-xs font-black {{ in_array($redeem->status_redeem, ['Claimed', 'Used'], true) ? 'bg-zinc-200 text-zinc-800' : 'bg-green-100 text-green-800' }}">
                                    {{ $redeem->status_redeem ?? 'Success' }}
                                </span>
                            </td>
                            <td class="px-5 py-4">{{ $redeem->claimedBy?->nama_pegawai ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-5 py-10 text-center text-gray-700">Belum ada riwayat redeem.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-gold/30 px-5 py-4">{{ $redeems->links() }}</div>
    </section>
@endsection
