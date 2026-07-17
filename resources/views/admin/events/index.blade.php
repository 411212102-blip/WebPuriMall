@extends('layouts.admin')
@section('title', 'Event Mall')
@section('page-title', 'Event Mall')
@section('content')
    <div class="rounded-xl border border-gold/30 bg-white shadow-glow">
        <div class="flex items-center justify-between border-b border-gold/30 px-5 py-4">
            <h3 class="text-lg font-black text-gray-900">Data Event</h3>
            <a href="{{ route('admin.events.create') }}" class="rounded-lg bg-deepRed px-4 py-2 font-black text-cream hover:bg-gold">Tambah Event</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full min-w-[820px] text-left text-sm">
                <thead class="bg-deepRed/5 text-xs uppercase tracking-wider text-gray-700"><tr><th class="px-5 py-3">Gambar</th><th class="px-5 py-3">Event</th><th class="px-5 py-3">Lokasi</th><th class="px-5 py-3">Periode</th><th class="px-5 py-3">Status</th><th class="px-5 py-3 text-right">Aksi</th></tr></thead>
                <tbody class="divide-y divide-line">
                    @forelse ($events as $event)
                        <tr><td class="px-5 py-4">@if ($event->gambar_event)<img src="{{ asset('storage/' . $event->gambar_event) }}" alt="{{ $event->nama_event }}" class="h-14 w-24 rounded-md border border-gold/30 object-cover">@else<span class="text-xs font-bold text-gray-700">Tanpa gambar</span>@endif</td><td class="px-5 py-4 font-bold text-gray-900">{{ $event->nama_event }}</td><td class="px-5 py-4">{{ $event->lokasi }}</td><td class="px-5 py-4">{{ $event->tgl_mulai?->format('d M Y') }} - {{ $event->tgl_selesai?->format('d M Y') }}</td><td class="px-5 py-4">{{ $event->is_active ? 'Aktif' : 'Nonaktif' }}</td><td class="px-5 py-4"><div class="flex justify-end gap-2"><a href="{{ route('admin.events.edit', $event->id_event) }}" class="rounded-lg border border-gold/30 px-3 py-2">Edit</a><form method="POST" action="{{ route('admin.events.destroy', $event->id_event) }}">@csrf @method('DELETE')<button class="rounded-lg border border-red-700 px-3 py-2 text-red-800">Hapus</button></form></div></td></tr>
                    @empty
                        <tr><td colspan="6" class="px-5 py-10 text-center text-gray-700">Belum ada event.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-gold/30 px-5 py-4">{{ $events->links() }}</div>
    </div>
@endsection

