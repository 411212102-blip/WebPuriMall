@extends('layouts.admin')
@section('title', 'Katalog Hadiah')
@section('page-title', 'Katalog Hadiah')
@section('content')
    <div class="rounded-xl border border-gold/30 bg-white shadow-glow">
        <div class="flex items-center justify-between border-b border-gold/30 px-5 py-4"><h3 class="text-lg font-black text-gray-900">Data Hadiah</h3><a href="{{ route('admin.hadiah.create') }}" class="rounded-lg bg-deepRed px-4 py-2 font-black text-cream hover:bg-gold">Tambah Hadiah</a></div>
        <div class="overflow-x-auto">
            <table class="w-full min-w-[780px] text-left text-sm">
                <thead class="bg-deepRed/5 text-xs uppercase tracking-wider text-gray-700"><tr><th class="px-5 py-3">Hadiah</th><th class="px-5 py-3">Poin</th><th class="px-5 py-3">Stok</th><th class="px-5 py-3">Status</th><th class="px-5 py-3 text-right">Aksi</th></tr></thead>
                <tbody class="divide-y divide-line">
                    @forelse ($hadiah as $item)
                        <tr><td class="px-5 py-4 font-bold text-gray-900">{{ $item->nama_hadiah }}</td><td class="px-5 py-4">{{ number_format($item->poin_dibutuhkan) }}</td><td class="px-5 py-4">{{ $item->stok }}</td><td class="px-5 py-4">{{ $item->is_active ? 'Aktif' : 'Nonaktif' }}</td><td class="px-5 py-4"><div class="flex justify-end gap-2"><a href="{{ route('admin.hadiah.edit', $item->id_hadiah) }}" class="rounded-lg border border-gold/30 px-3 py-2">Edit</a><form method="POST" action="{{ route('admin.hadiah.destroy', $item->id_hadiah) }}">@csrf @method('DELETE')<button class="rounded-lg border border-red-700 px-3 py-2 text-red-800">Hapus</button></form></div></td></tr>
                    @empty
                        <tr><td colspan="5" class="px-5 py-10 text-center text-gray-700">Belum ada hadiah.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-gold/30 px-5 py-4">{{ $hadiah->links() }}</div>
    </div>
@endsection

