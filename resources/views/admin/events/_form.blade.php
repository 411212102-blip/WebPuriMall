@csrf
<div class="grid gap-5 md:grid-cols-2">
    <div class="md:col-span-2">
        <label class="mb-2 block text-sm font-bold text-gray-900">Nama Event</label>
        <div class="relative">
            <i data-lucide="tag" class="pointer-events-none absolute left-3 top-3.5 h-5 w-5 text-deepRed"></i>
            <input name="nama_event" value="{{ old('nama_event', $event->nama_event) }}" required maxlength="200" class="w-full rounded-lg border border-gold/40 bg-white py-3 pl-11 pr-4 text-gray-900 outline-none focus:border-deepRed focus:ring-2 focus:ring-gold/20">
        </div>
        @error('nama_event') <p class="mt-1 text-sm text-red-800">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="mb-2 block text-sm font-bold text-gray-900">Lokasi</label>
        <div class="relative">
            <i data-lucide="map-pin" class="pointer-events-none absolute left-3 top-3.5 h-5 w-5 text-deepRed"></i>
            <select name="lokasi" required class="w-full rounded-lg border border-gold/40 bg-white py-3 pl-11 pr-4 text-gray-900 outline-none focus:border-deepRed focus:ring-2 focus:ring-gold/20">
                @foreach (['Outdoor', 'Indoor Center Court 1', 'Indoor Center Court 2', 'Indoor Court 3'] as $lokasi)
                    <option value="{{ $lokasi }}" @selected(old('lokasi', $event->lokasi) === $lokasi)>{{ $lokasi }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div>
        <label class="mb-2 block text-sm font-bold text-gray-900">Status</label>
        <div class="relative">
            <i data-lucide="circle-check" class="pointer-events-none absolute left-3 top-3.5 h-5 w-5 text-deepRed"></i>
            <select name="is_active" class="w-full rounded-lg border border-gold/40 bg-white py-3 pl-11 pr-4 text-gray-900 outline-none focus:border-deepRed focus:ring-2 focus:ring-gold/20">
                <option value="1" @selected(old('is_active', $event->is_active ?? 1) == 1)>Aktif</option>
                <option value="0" @selected(old('is_active', $event->is_active ?? 1) == 0)>Nonaktif</option>
            </select>
        </div>
    </div>

    <div>
        <label class="mb-2 block text-sm font-bold text-gray-900">Tanggal Mulai</label>
        <div class="relative">
            <i data-lucide="calendar-days" class="pointer-events-none absolute left-3 top-3.5 h-5 w-5 text-deepRed"></i>
            <input type="date" name="tgl_mulai" value="{{ old('tgl_mulai', optional($event->tgl_mulai)->format('Y-m-d')) }}" required class="w-full rounded-lg border border-gold/40 bg-white py-3 pl-11 pr-4 text-gray-900 outline-none focus:border-deepRed focus:ring-2 focus:ring-gold/20">
        </div>
    </div>

    <div>
        <label class="mb-2 block text-sm font-bold text-gray-900">Tanggal Selesai</label>
        <div class="relative">
            <i data-lucide="calendar-check" class="pointer-events-none absolute left-3 top-3.5 h-5 w-5 text-deepRed"></i>
            <input type="date" name="tgl_selesai" value="{{ old('tgl_selesai', optional($event->tgl_selesai)->format('Y-m-d')) }}" required class="w-full rounded-lg border border-gold/40 bg-white py-3 pl-11 pr-4 text-gray-900 outline-none focus:border-deepRed focus:ring-2 focus:ring-gold/20">
        </div>
    </div>

    <div class="md:col-span-2">
        <label class="mb-2 block text-sm font-bold text-gray-900">Gambar Promosi Event</label>
        <div class="grid gap-4 md:grid-cols-[1fr_240px]">
            <div>
                <div class="relative">
                    <i data-lucide="image-up" class="pointer-events-none absolute left-3 top-3.5 h-5 w-5 text-deepRed"></i>
                    <input id="gambar-event" type="file" name="gambar_event" accept="image/jpeg,image/png" class="w-full rounded-lg border border-gold/40 bg-white py-3 pl-11 pr-4 text-sm text-gray-900 outline-none file:mr-3 file:rounded-md file:border-0 file:bg-deepRed file:px-3 file:py-1 file:font-bold file:text-cream focus:border-deepRed">
                </div>
                <p class="mt-2 text-xs font-semibold text-gray-700">JPG atau PNG. Maksimal 2 MB. Gunakan rasio lanskap agar kartu publik terlihat rapi.</p>
                @error('gambar_event') <p class="mt-1 text-sm text-red-800">{{ $message }}</p> @enderror
            </div>
            <div class="overflow-hidden rounded-lg border border-gold/30 bg-cream">
                <img id="gambar-event-preview"
                     src="{{ $event->gambar_event ? asset('storage/' . $event->gambar_event) : '' }}"
                     alt="Pratinjau gambar event"
                     class="{{ $event->gambar_event ? '' : 'hidden' }} aspect-video h-full w-full object-cover">
                <div id="gambar-event-placeholder" class="{{ $event->gambar_event ? 'hidden' : '' }} grid aspect-video place-items-center px-4 text-center text-xs font-bold text-gray-700">
                    Pratinjau gambar event
                </div>
            </div>
        </div>
    </div>

    <div class="md:col-span-2">
        <label class="mb-2 block text-sm font-bold text-gray-900">Deskripsi</label>
        <div class="relative">
            <i data-lucide="align-left" class="pointer-events-none absolute left-3 top-3.5 h-5 w-5 text-deepRed"></i>
            <textarea name="deskripsi" rows="4" class="w-full rounded-lg border border-gold/40 bg-white py-3 pl-11 pr-4 text-gray-900 outline-none focus:border-deepRed focus:ring-2 focus:ring-gold/20">{{ old('deskripsi', $event->deskripsi) }}</textarea>
        </div>
    </div>
</div>
<div class="mt-6 flex gap-3">
    <button class="rounded-lg bg-deepRed px-5 py-3 font-black text-cream hover:bg-gold hover:text-deepRed">Simpan</button>
    <a href="{{ route('admin.events.index') }}" class="rounded-lg border border-gold/40 px-5 py-3 font-black text-gray-800 hover:border-deepRed">Batal</a>
</div>

<script>
    document.getElementById('gambar-event')?.addEventListener('change', (event) => {
        const file = event.target.files[0];
        if (!file) return;

        const preview = document.getElementById('gambar-event-preview');
        preview.src = URL.createObjectURL(file);
        preview.classList.remove('hidden');
        document.getElementById('gambar-event-placeholder').classList.add('hidden');
    });
</script>
