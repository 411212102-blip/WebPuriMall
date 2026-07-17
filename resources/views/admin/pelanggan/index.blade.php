@extends('layouts.admin')

@section('title', 'Data Pelanggan')
@section('page-title', 'Data Pelanggan')

@section('content')
    <section class="rounded-xl border border-gold/30 bg-white shadow-glow">
        <div class="flex flex-wrap items-end justify-between gap-4 border-b border-gold/30 px-5 py-4">
            <div>
                <h3 class="text-lg font-black text-gray-900">Direktori Member</h3>
                <p class="text-sm text-gray-700">Identitas pelanggan terdaftar dan nomor member otomatis.</p>
            </div>
            <form method="GET" class="flex gap-2">
                <input name="search" value="{{ $search }}" placeholder="Cari member..." class="rounded-md border border-gold/40 px-4 py-2 text-sm text-gray-900 outline-none focus:border-deepRed">
                <button class="rounded-md bg-deepRed px-4 py-2 text-sm font-black text-cream hover:bg-gold hover:text-deepRed">Cari</button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[1000px] text-left text-sm">
                <thead class="bg-deepRed/5 text-xs uppercase tracking-wider text-gray-700">
                    <tr>
                        <th class="px-5 py-3">No. Pelanggan</th>
                        <th class="px-5 py-3">Nama</th>
                        <th class="px-5 py-3">Alamat</th>
                        <th class="px-5 py-3">No. HP / WA</th>
                        <th class="px-5 py-3">Email</th>
                        <th class="px-5 py-3">Cluster</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line text-gray-900">
                    @forelse ($pelanggan as $member)
                        <tr>
                            <td class="px-5 py-4 font-black text-deepRed">{{ $member->no_pelanggan ?? '-' }}</td>
                            <td class="px-5 py-4 font-bold">{{ $member->nama_pelanggan }}</td>
                            <td class="max-w-xs px-5 py-4">{{ $member->alamat ?? '-' }}</td>
                            <td class="px-5 py-4">{{ $member->no_whatsapp_pelanggan }}</td>
                            <td class="px-5 py-4">{{ $member->email_pelanggan }}</td>
                            <td class="px-5 py-4">{{ $member->cluster?->nama_cluster ?? 'Belum disegmentasi' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-5 py-10 text-center text-gray-700">Belum ada pelanggan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-gold/30 px-5 py-4">{{ $pelanggan->links() }}</div>
    </section>
@endsection
