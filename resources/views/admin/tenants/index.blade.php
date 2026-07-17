@extends('layouts.admin')

@section('title', 'Master Tenant')
@section('page-title', 'Master Tenant')

@section('content')
    <div class="rounded-xl border border-gold/30 bg-white shadow-glow">
        <div class="flex items-center justify-between border-b border-gold/30 px-5 py-4">
            <div>
                <h3 class="text-lg font-black text-gray-900">Data Tenant</h3>
                <p class="text-sm text-gray-700">Kelola merchant dan direktori toko mall.</p>
            </div>
            <a href="{{ route('admin.tenants.create') }}" class="rounded-lg bg-deepRed px-4 py-2 font-black text-cream hover:bg-gold">Tambah Tenant</a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[900px] text-left text-sm">
                <thead class="bg-deepRed/5 text-xs uppercase tracking-wider text-gray-700">
                    <tr>
                        <th class="px-5 py-3">Logo</th>
                        <th class="px-5 py-3">Tenant</th>
                        <th class="px-5 py-3">Kategori</th>
                        <th class="px-5 py-3">Unit</th>
                        <th class="px-5 py-3">Lantai</th>
                        <th class="px-5 py-3">Telp</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line">
                    @forelse ($tenants as $tenant)
                        <tr>
                            <td class="px-5 py-4">
                                @if ($tenant->gambar_tenant)
                                    <img src="{{ asset('storage/' . $tenant->gambar_tenant) }}" alt="{{ $tenant->nama_tenant }}" class="h-12 w-12 rounded-lg border border-gold/30 object-cover">
                                @else
                                    <span class="grid h-12 w-12 place-items-center rounded-lg bg-cream text-xs font-black text-deepRed">N/A</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 font-bold text-gray-900">{{ $tenant->nama_tenant }}</td>
                            <td class="px-5 py-4">{{ $tenant->nama_kategori }}</td>
                            <td class="px-5 py-4">{{ $tenant->no_unit ?? '-' }}</td>
                            <td class="px-5 py-4">{{ $tenant->lantai ?? '-' }}</td>
                            <td class="px-5 py-4">{{ $tenant->no_telp ?? '-' }}</td>
                            <td class="px-5 py-4">{{ $tenant->is_active ? 'Aktif' : 'Nonaktif' }}</td>
                            <td class="px-5 py-4">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.tenants.edit', $tenant->id_tenant) }}" class="rounded-lg border border-gold/30 px-3 py-2 font-bold hover:border-deepRed">Edit</a>
                                    <form method="POST" action="{{ route('admin.tenants.destroy', $tenant->id_tenant) }}" onsubmit="return confirm('Hapus tenant ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="rounded-lg border border-red-700 px-3 py-2 font-bold text-red-800 hover:bg-red-700 hover:text-cream">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="px-5 py-10 text-center text-gray-700">Belum ada tenant.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-gold/30 px-5 py-4">{{ $tenants->links() }}</div>
    </div>
@endsection

