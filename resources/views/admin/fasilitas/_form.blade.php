@csrf
<div class="grid gap-5">
    <div>
        <label class="mb-2 block text-sm font-bold text-gray-900">Nama Fasilitas</label>
        <div class="relative">
            <i data-lucide="building-2" class="pointer-events-none absolute left-3 top-3.5 h-5 w-5 text-deepRed"></i>
            <input name="nama_fasilitas" value="{{ old('nama_fasilitas', $fasilitas->nama_fasilitas) }}" required maxlength="150" class="w-full rounded-lg border border-gold/40 bg-white py-3 pl-11 pr-4 text-gray-900 outline-none focus:border-deepRed focus:ring-2 focus:ring-gold/20">
        </div>
    </div>
    <div>
        <label class="mb-2 block text-sm font-bold text-gray-900">Lokasi / Lantai</label>
        <div class="relative">
            <i data-lucide="map-pin" class="pointer-events-none absolute left-3 top-3.5 h-5 w-5 text-deepRed"></i>
            <input name="lokasi_lantai" value="{{ old('lokasi_lantai', $fasilitas->lokasi_lantai) }}" required maxlength="50" class="w-full rounded-lg border border-gold/40 bg-white py-3 pl-11 pr-4 text-gray-900 outline-none focus:border-deepRed focus:ring-2 focus:ring-gold/20">
        </div>
    </div>
    <div>
        <label class="mb-2 block text-sm font-bold text-gray-900">Deskripsi</label>
        <div class="relative">
            <i data-lucide="align-left" class="pointer-events-none absolute left-3 top-3.5 h-5 w-5 text-deepRed"></i>
            <textarea name="deskripsi" rows="4" class="w-full rounded-lg border border-gold/40 bg-white py-3 pl-11 pr-4 text-gray-900 outline-none focus:border-deepRed focus:ring-2 focus:ring-gold/20">{{ old('deskripsi', $fasilitas->deskripsi) }}</textarea>
        </div>
    </div>
</div>
<div class="mt-6 flex gap-3">
    <button class="rounded-lg bg-deepRed px-5 py-3 font-black text-cream hover:bg-gold hover:text-deepRed">Simpan</button>
    <a href="{{ route('admin.fasilitas.index') }}" class="rounded-lg border border-gold/40 px-5 py-3 font-black text-gray-800 hover:border-deepRed">Batal</a>
</div>
