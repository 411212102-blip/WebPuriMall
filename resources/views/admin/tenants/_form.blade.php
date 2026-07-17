@csrf

<div class="grid gap-4 md:grid-cols-2">
    <div>
        <label class="mb-2 block text-sm font-bold text-gray-800">Kategori</label>
        <select name="id_kategori" required class="w-full rounded-md border border-gold/30 bg-white px-4 py-3 text-gray-900 outline-none focus:border-deepRed">
            <option value="">Pilih kategori</option>
            @foreach ($kategori as $item)
                <option value="{{ $item->id_kategori }}" @selected(old('id_kategori', $tenant->id_kategori) == $item->id_kategori)>
                    {{ $item->nama_kategori }}
                </option>
            @endforeach
        </select>
        @error('id_kategori') <p class="mt-1 text-sm text-red-800">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="mb-2 block text-sm font-bold text-gray-800">Nama Tenant</label>
        <input name="nama_tenant" value="{{ old('nama_tenant', $tenant->nama_tenant) }}" required maxlength="150" class="w-full rounded-md border border-gold/30 bg-white px-4 py-3 text-gray-900 outline-none focus:border-deepRed">
        @error('nama_tenant') <p class="mt-1 text-sm text-red-800">{{ $message }}</p> @enderror
    </div>

    <div class="md:col-span-2">
        <label class="mb-2 block text-sm font-bold text-gray-800">Logo / Foto Tenant</label>
        @if ($tenant->gambar_tenant)
            <img src="{{ asset('storage/' . $tenant->gambar_tenant) }}" alt="{{ $tenant->nama_tenant }}" class="mb-3 h-20 w-20 rounded-lg border border-gold/30 object-cover">
        @endif
        <input type="file" name="gambar_tenant" accept="image/jpeg,image/png,image/webp" class="w-full rounded-md border border-gold/30 bg-white px-4 py-3 text-gray-900 outline-none focus:border-deepRed">
        <p class="mt-1 text-xs text-gray-700">JPG, PNG, atau WEBP. Maksimal 2 MB.</p>
        @error('gambar_tenant') <p class="mt-1 text-sm text-red-800">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="mb-2 block text-sm font-bold text-gray-800">No Unit</label>
        <input name="no_unit" value="{{ old('no_unit', $tenant->no_unit) }}" maxlength="20" class="w-full rounded-md border border-gold/30 bg-white px-4 py-3 text-gray-900 outline-none focus:border-deepRed">
    </div>

    <div>
        <label class="mb-2 block text-sm font-bold text-gray-800">Lantai</label>
        <input type="number" name="lantai" value="{{ old('lantai', $tenant->lantai) }}" min="0" max="255" class="w-full rounded-md border border-gold/30 bg-white px-4 py-3 text-gray-900 outline-none focus:border-deepRed">
    </div>

    <div>
        <label class="mb-2 block text-sm font-bold text-gray-800">No Telp</label>
        <input name="no_telp" value="{{ old('no_telp', $tenant->no_telp) }}" maxlength="15" class="w-full rounded-md border border-gold/30 bg-white px-4 py-3 text-gray-900 outline-none focus:border-deepRed">
    </div>

    <div>
        <label class="mb-2 block text-sm font-bold text-gray-800">Status</label>
        <select name="is_active" required class="w-full rounded-md border border-gold/30 bg-white px-4 py-3 text-gray-900 outline-none focus:border-deepRed">
            <option value="1" @selected(old('is_active', $tenant->is_active ?? 1) == 1)>Aktif</option>
            <option value="0" @selected(old('is_active', $tenant->is_active ?? 1) == 0)>Nonaktif</option>
        </select>
    </div>
</div>

<div class="mt-6 flex gap-3">
    <button class="rounded-md bg-deepRed px-5 py-3 font-black text-cream hover:bg-gold">Simpan</button>
    <a href="{{ route('admin.tenants.index') }}" class="rounded-md border border-gold/30 px-5 py-3 font-black text-gray-800 hover:border-deepRed">Batal</a>
</div>

