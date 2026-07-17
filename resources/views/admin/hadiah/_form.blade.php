@csrf
<div class="grid gap-5 md:grid-cols-2">
    <div class="md:col-span-2">
        <label class="mb-2 block text-sm font-bold text-gray-900">Nama Hadiah</label>
        <div class="relative">
            <i data-lucide="gift" class="pointer-events-none absolute left-3 top-3.5 h-5 w-5 text-deepRed"></i>
            <input name="nama_hadiah" value="{{ old('nama_hadiah', $hadiah->nama_hadiah) }}" required maxlength="200" class="w-full rounded-lg border border-gold/40 bg-white py-3 pl-11 pr-4 text-gray-900 outline-none focus:border-deepRed focus:ring-2 focus:ring-gold/20">
        </div>
    </div>
    <div>
        <label class="mb-2 block text-sm font-bold text-gray-900">Poin Dibutuhkan</label>
        <div class="relative">
            <i data-lucide="coins" class="pointer-events-none absolute left-3 top-3.5 h-5 w-5 text-deepRed"></i>
            <input type="number" name="poin_dibutuhkan" value="{{ old('poin_dibutuhkan', $hadiah->poin_dibutuhkan) }}" required min="1" class="w-full rounded-lg border border-gold/40 bg-white py-3 pl-11 pr-4 text-gray-900 outline-none focus:border-deepRed focus:ring-2 focus:ring-gold/20">
        </div>
    </div>
    <div>
        <label class="mb-2 block text-sm font-bold text-gray-900">Stok</label>
        <div class="relative">
            <i data-lucide="package" class="pointer-events-none absolute left-3 top-3.5 h-5 w-5 text-deepRed"></i>
            <input type="number" name="stok" value="{{ old('stok', $hadiah->stok ?? 0) }}" required min="0" class="w-full rounded-lg border border-gold/40 bg-white py-3 pl-11 pr-4 text-gray-900 outline-none focus:border-deepRed focus:ring-2 focus:ring-gold/20">
        </div>
    </div>
    <div>
        <label class="mb-2 block text-sm font-bold text-gray-900">Gambar URL</label>
        <div class="relative">
            <i data-lucide="link" class="pointer-events-none absolute left-3 top-3.5 h-5 w-5 text-deepRed"></i>
            <input name="gambar_url" value="{{ old('gambar_url', $hadiah->gambar_url) }}" class="w-full rounded-lg border border-gold/40 bg-white py-3 pl-11 pr-4 text-gray-900 outline-none focus:border-deepRed focus:ring-2 focus:ring-gold/20">
        </div>
    </div>
    <div>
        <label class="mb-2 block text-sm font-bold text-gray-900">Status</label>
        <div class="relative">
            <i data-lucide="circle-check" class="pointer-events-none absolute left-3 top-3.5 h-5 w-5 text-deepRed"></i>
            <select name="is_active" class="w-full rounded-lg border border-gold/40 bg-white py-3 pl-11 pr-4 text-gray-900 outline-none focus:border-deepRed focus:ring-2 focus:ring-gold/20">
                <option value="1" @selected(old('is_active', $hadiah->is_active ?? 1) == 1)>Aktif</option>
                <option value="0" @selected(old('is_active', $hadiah->is_active ?? 1) == 0)>Nonaktif</option>
            </select>
        </div>
    </div>
</div>
<div class="mt-6 flex gap-3">
    <button class="rounded-lg bg-deepRed px-5 py-3 font-black text-cream hover:bg-gold hover:text-deepRed">Simpan</button>
    <a href="{{ route('admin.hadiah.index') }}" class="rounded-lg border border-gold/40 px-5 py-3 font-black text-gray-800 hover:border-deepRed">Batal</a>
</div>
