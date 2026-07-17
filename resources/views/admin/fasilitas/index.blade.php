@extends('layouts.admin')
@section('title', 'Fasilitas')
@section('page-title', 'Fasilitas Mall')
@section('content')
    <div class="rounded-xl border border-gold/30 bg-white shadow-glow">
        <div class="flex items-center justify-between border-b border-gold/30 px-5 py-4"><h3 class="text-lg font-black text-gray-900">Data Fasilitas</h3><a href="{{ route('admin.fasilitas.create') }}" class="rounded-lg bg-deepRed px-4 py-2 font-black text-cream hover:bg-gold">Tambah Fasilitas</a></div>
        <div class="overflow-x-auto">
            <table class="w-full min-w-[760px] text-left text-sm">
                <thead class="bg-deepRed/5 text-xs uppercase tracking-wider text-gray-700"><tr><th class="px-5 py-3">Fasilitas</th><th class="px-5 py-3">Lokasi</th><th class="px-5 py-3">Deskripsi</th><th class="px-5 py-3 text-right">Aksi</th></tr></thead>
                <tbody class="divide-y divide-line">
                    @forelse ($fasilitas as $item)
                        <tr><td class="px-5 py-4 font-bold text-gray-900">{{ $item->nama_fasilitas }}</td><td class="px-5 py-4">{{ $item->lokasi_lantai }}</td><td class="px-5 py-4 text-gray-700">{{ $item->deskripsi ?? '-' }}</td><td class="px-5 py-4"><div class="flex justify-end gap-2"><a href="{{ route('admin.fasilitas.edit', $item->id_fasilitas) }}" class="rounded-lg border border-gold/30 px-3 py-2">Edit</a><form method="POST" action="{{ route('admin.fasilitas.destroy', $item->id_fasilitas) }}">@csrf @method('DELETE')<button class="rounded-lg border border-red-700 px-3 py-2 text-red-800">Hapus</button></form></div></td></tr>
                    @empty
                        <tr><td colspan="4" class="px-5 py-10 text-center text-gray-700">Belum ada fasilitas.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-gold/30 px-5 py-4">{{ $fasilitas->links() }}</div>
    </div>
@endsection

