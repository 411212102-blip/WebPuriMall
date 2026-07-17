<!doctype html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Puri Indah Mall Loyalty</title>
    <link rel="icon" href="{{ asset('images/logo-white.png') }}" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        cream: '#FDF5E6',
                        deepRed: '#8B0000',
                        gold: '#D4AF37',
                        wine: '#5F0000',
                    },
                    boxShadow: {
                        premium: '0 18px 50px rgba(139, 0, 0, 0.18)',
                    },
                },
            },
        };
    </script>
</head>
<body class="bg-cream text-zinc-900 antialiased">
    @php
        $member = auth('pelanggan')->user();
        $internal = auth('pegawai')->user();
    @endphp

    <header class="sticky top-0 z-50 border-b border-gold/30 bg-deepRed text-white shadow-lg">
        <div class="mx-auto max-w-7xl px-4 sm:px-5">
            <div class="flex min-h-16 items-center justify-between gap-3 py-3">
                <a href="#home" class="flex items-center gap-3">
                    <!-- <div class="grid h-11 w-11 shrink-0 place-items-center rounded-full border border-gold bg-white text-base font-black text-deepRed">PM</div> -->
                    <img src="{{ asset('images/logo-white.png') }}" alt="Puri Indah Mall Loyalty logo with gold text and white emblem" class="h-12 w-12 object-contain ">
                    <div class="leading-tight">
                        <p class="text-[11px] font-black uppercase tracking-[0.22em] text-gold">Puri Indah</p>
                        <p class="text-base font-black">Mall Loyalty</p>
                    </div>
                </a>

                <button type="button"
                        class="grid h-10 w-10 place-items-center rounded-md border border-gold/50 text-gold md:hidden"
                        onclick="document.getElementById('mobileMenu').classList.toggle('hidden')"
                        aria-label="Buka menu">
                    <span class="text-2xl leading-none">≡</span>
                </button>

                <nav class="hidden items-center gap-5 text-xs font-black uppercase tracking-wide md:flex">
                    <a href="#home" class="hover:text-gold">Home</a>
                    <a href="#news-promotion" class="hover:text-gold">News & Promotion</a>
                    <a href="#service" class="hover:text-gold">Service</a>
                    <a href="#shopping-directory" class="hover:text-gold">Shopping Directory</a>
                    <a href="#rewards-catalog" class="hover:text-gold">Rewards</a>
                    <a href="#contact-us" class="hover:text-gold">Contact Us</a>
                </nav>
            </div>

            <div class="hidden border-t border-gold/20 py-3 md:block">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    @if ($member)
                        <div class="flex flex-wrap items-center gap-2 text-sm">
                            <span class="rounded-full bg-gold px-4 py-2 font-black text-deepRed">
                                Poin Anda: {{ number_format($member->total_poin, 0, ',', '.') }} Poin
                            </span>
                            <span class="rounded-full border border-gold/60 px-4 py-2 font-bold text-gold">
                                {{ $memberCluster?->nama_cluster ?? 'Belum Dicluster' }}
                            </span>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('pelanggan.dashboard') }}" class="rounded-full bg-white px-4 py-2 text-sm font-black text-deepRed hover:bg-gold">Dashboard Saya</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="rounded-full border border-gold px-4 py-2 text-sm font-black text-gold hover:bg-gold hover:text-deepRed">Logout</button>
                            </form>
                        </div>
                    @elseif ($internal)
                        <div class="rounded-full bg-gold px-4 py-2 text-sm font-black text-deepRed">
                            {{ $internal->nama_pegawai }} · {{ strtoupper($internal->role) }}
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="{{ $internal->role === 'manager' ? route('mgmt.dashboard') : route('staff.dashboard') }}" class="rounded-full bg-white px-4 py-2 text-sm font-black text-deepRed hover:bg-gold">Dashboard Internal</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="rounded-full border border-gold px-4 py-2 text-sm font-black text-gold hover:bg-gold hover:text-deepRed">Logout</button>
                            </form>
                        </div>
                    @else
                        <p class="text-sm font-semibold text-white/80">Masuk untuk mengumpulkan poin dari transaksi belanja Anda.</p>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('login') }}" class="rounded-full border border-gold px-4 py-2 text-sm font-black text-gold hover:bg-gold hover:text-deepRed">Login</a>
                            <a href="{{ route('register') }}" class="rounded-full bg-gold px-4 py-2 text-sm font-black text-deepRed hover:bg-white">Daftar</a>
                        </div>
                    @endif
                </div>
            </div>

            <div id="mobileMenu" class="hidden border-t border-gold/20 py-4 md:hidden">
                <nav class="grid gap-2 text-sm font-black uppercase tracking-wide">
                    <a href="#home" class="rounded-md px-3 py-2 hover:bg-white/10">Home</a>
                    <a href="#news-promotion" class="rounded-md px-3 py-2 hover:bg-white/10">News & Promotion</a>
                    <a href="#service" class="rounded-md px-3 py-2 hover:bg-white/10">Service</a>
                    <a href="#shopping-directory" class="rounded-md px-3 py-2 hover:bg-white/10">Shopping Directory</a>
                    <a href="#rewards-catalog" class="rounded-md px-3 py-2 hover:bg-white/10">Rewards</a>
                    <a href="#contact-us" class="rounded-md px-3 py-2 hover:bg-white/10">Contact Us</a>
                </nav>

                <div class="mt-4 grid gap-2 border-t border-gold/20 pt-4">
                    @if ($member)
                        <div class="rounded-md bg-gold px-4 py-3 text-sm font-black text-deepRed">
                            Poin Anda: {{ number_format($member->total_poin, 0, ',', '.') }} Poin
                        </div>
                        <div class="rounded-md border border-gold/60 px-4 py-3 text-sm font-bold text-gold">
                            Cluster: {{ $memberCluster?->nama_cluster ?? 'Belum Dicluster' }}
                        </div>
                        <a href="{{ route('pelanggan.dashboard') }}" class="rounded-md bg-white px-4 py-3 text-center font-black text-deepRed">Dashboard Saya</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="w-full rounded-md border border-gold px-4 py-3 font-black text-gold">Logout</button>
                        </form>
                    @elseif ($internal)
                        <a href="{{ $internal->role === 'manager' ? route('mgmt.dashboard') : route('staff.dashboard') }}" class="rounded-md bg-gold px-4 py-3 text-center font-black text-deepRed">Dashboard Internal</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="w-full rounded-md border border-gold px-4 py-3 font-black text-gold">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="rounded-md border border-gold px-4 py-3 text-center font-black text-gold">Login</a>
                        <a href="{{ route('register') }}" class="rounded-md bg-gold px-4 py-3 text-center font-black text-deepRed">Daftar</a>
                    @endif
                </div>
            </div>
        </div>
    </header>

    <main>
        <section id="home" class="relative overflow-hidden bg-deepRed">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_20%_10%,rgba(255,255,255,0.26),transparent_24%),linear-gradient(135deg,#B00010_0%,#E11D48_48%,#5F0000_100%)]"></div>
            <div class="relative mx-auto grid min-h-[560px] max-w-7xl gap-8 px-4 py-12 sm:px-5 md:grid-cols-[1.05fr_0.95fr] md:items-center md:py-16">
                <div class="text-white">
                    <p class="text-xs font-black uppercase tracking-[0.28em] text-gold">Shop More · Earn More · Redeem Better</p>
                    <h1 class="mt-4 text-4xl font-black leading-tight sm:text-5xl lg:text-7xl">Puri Indah Mall Loyalty</h1>
                    <p class="mt-5 max-w-2xl text-base leading-7 text-white/85 sm:text-lg">
                        Kumpulkan poin dari struk belanja, pantau status verifikasi, temukan promo tenant, dan tukarkan reward eksklusif member.
                    </p>
                    <div class="mt-7 flex flex-col gap-3 sm:flex-row">
                        @if ($member)
                            <a href="{{ route('pelanggan.upload-struk') }}" class="rounded-full bg-gold px-6 py-3 text-center font-black text-deepRed shadow-premium hover:bg-white">Upload Struk</a>
                            <a href="#rewards-catalog" class="rounded-full border border-white/70 px-6 py-3 text-center font-black text-white hover:border-gold hover:text-gold">Lihat Reward</a>
                        @else
                            <a href="{{ route('register') }}" class="rounded-full bg-gold px-6 py-3 text-center font-black text-deepRed shadow-premium hover:bg-white">Daftar Member</a>
                            <a href="#news-promotion" class="rounded-full border border-white/70 px-6 py-3 text-center font-black text-white hover:border-gold hover:text-gold">Lihat Promo</a>
                        @endif
                    </div>
                </div>

                <div class="rounded-lg border border-gold/40 bg-white/15 p-4 shadow-premium backdrop-blur">
                    <div class="rounded-md bg-cream p-5 text-deepRed">
                        <p class="text-center text-xs font-black uppercase tracking-[0.22em]">Member Snapshot</p>
                        <div class="mt-5 grid gap-3 sm:grid-cols-3">
                            <div class="rounded-md bg-white p-4 text-center shadow">
                                <p class="text-2xl font-black">{{ number_format($member?->total_poin ?? 0, 0, ',', '.') }}</p>
                                <p class="mt-1 text-xs font-bold text-zinc-500">Poin</p>
                            </div>
                            <div class="rounded-md bg-white p-4 text-center shadow">
                                <p class="text-2xl font-black">{{ $katalogHadiah->count() }}</p>
                                <p class="mt-1 text-xs font-bold text-zinc-500">Reward</p>
                            </div>
                            <div class="rounded-md bg-white p-4 text-center shadow">
                                <p class="text-2xl font-black">{{ $tenants->count() }}</p>
                                <p class="mt-1 text-xs font-bold text-zinc-500">Tenant</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="news-promotion" class="mx-auto max-w-7xl px-4 py-12 sm:px-5 lg:py-16">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="text-xs font-black uppercase tracking-[0.22em] text-gold">News & Promotion</p>
                    <h2 class="mt-2 text-3xl font-black text-deepRed">What's Hot</h2>
                </div>
                <div class="hidden h-1 flex-1 bg-deepRed sm:block"></div>
            </div>

            <div class="mt-7 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @forelse ($events as $event)
                    <article class="group overflow-hidden rounded-lg border border-gold/30 bg-white shadow-lg transition duration-300 hover:-translate-y-1 hover:shadow-xl">
                        <div class="overflow-hidden bg-cream">
                            @if ($event->gambar_event)
                                {{-- Upload event memakai public/storage. Jalankan "php artisan storage:link" bila instalasi dipindahkan ke disk public Laravel standar. --}}
                                <img src="{{ asset('storage/' . $event->gambar_event) }}" alt="{{ $event->nama_event }}" class="aspect-video h-56 w-full rounded-t-xl object-cover transition duration-300 group-hover:scale-105">
                            @else
                                <div class="grid aspect-video h-56 w-full place-items-center rounded-t-xl bg-deepRed/5 text-deepRed">
                                    <i data-lucide="calendar-days" class="h-12 w-12"></i>
                                </div>
                            @endif
                        </div>
                        <div class="p-5">
                            <p class="text-xs font-black uppercase tracking-wide text-gold">Event · {{ $event->lokasi }}</p>
                            <h3 class="mt-3 text-xl font-black text-deepRed">{{ $event->nama_event }}</h3>
                            <p class="mt-2 text-sm font-semibold text-zinc-600">
                                {{ \Illuminate\Support\Carbon::parse($event->tgl_mulai)->format('d M Y') }}
                                -
                                {{ \Illuminate\Support\Carbon::parse($event->tgl_selesai)->format('d M Y') }}
                            </p>
                            <p class="mt-3 line-clamp-3 text-sm leading-6 text-zinc-700">{{ $event->deskripsi ?? 'Acara terbaru di Puri Indah Mall.' }}</p>
                        </div>
                    </article>
                @empty
                    <article class="rounded-lg border border-gold/30 bg-white p-5 text-zinc-600 shadow">Belum ada event aktif.</article>
                @endforelse

                @foreach ($promos as $promo)
                    <article class="rounded-lg border border-gold/30 bg-white p-5 shadow-lg transition duration-300 hover:-translate-y-1 hover:shadow-xl">
                        <p class="text-xs font-black uppercase tracking-wide text-gold">Promo Tenant</p>
                        <h3 class="mt-3 text-xl font-black text-deepRed">{{ $promo->judul ?? $promo->nama_promo ?? 'Promo Spesial' }}</h3>
                        <p class="mt-3 line-clamp-3 text-sm leading-6 text-zinc-700">{{ $promo->deskripsi ?? $promo->keterangan ?? 'Nikmati penawaran khusus tenant pilihan.' }}</p>
                    </article>
                @endforeach
            </div>
        </section>

        <section id="service" class="bg-white/70 py-12 lg:py-16">
            <div class="mx-auto max-w-7xl px-4 sm:px-5">
                <p class="text-xs font-black uppercase tracking-[0.22em] text-gold">Service</p>
                <h2 class="mt-2 text-3xl font-black text-deepRed">Fasilitas Mall</h2>
                <div class="mt-7 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
                    @forelse ($fasilitas as $item)
                        @php
                            $facilityName = strtolower($item->nama_fasilitas);
                            $facilityIcon = match (true) {
                                str_contains($facilityName, 'toilet') => 'accessibility',
                                str_contains($facilityName, 'nursery'), str_contains($facilityName, 'baby') => 'baby',
                                str_contains($facilityName, 'atm'), str_contains($facilityName, 'bank') => 'landmark',
                                str_contains($facilityName, 'mushola'), str_contains($facilityName, 'prayer') => 'moon-star',
                                str_contains($facilityName, 'charging') => 'battery-charging',
                                str_contains($facilityName, 'wheelchair') => 'accessibility',
                                str_contains($facilityName, 'informasi') => 'info',
                                default => 'building-2',
                            };
                        @endphp
                        <article class="rounded-lg border border-gold/25 bg-cream p-5 transition duration-300 hover:-translate-y-1 hover:shadow-lg">
                            <div class="grid h-12 w-12 place-items-center rounded-full bg-deepRed text-gold">
                                <i data-lucide="{{ $facilityIcon }}" class="h-6 w-6"></i>
                            </div>
                            <h3 class="mt-4 font-black text-deepRed">{{ $item->nama_fasilitas }}</h3>
                            <p class="mt-1 text-sm font-semibold text-zinc-500">{{ $item->lokasi_lantai }}</p>
                            <p class="mt-3 text-sm leading-6 text-zinc-700">{{ $item->deskripsi ?? 'Fasilitas tersedia untuk kenyamanan pengunjung.' }}</p>
                        </article>
                    @empty
                        <p class="text-zinc-600">Data fasilitas belum tersedia.</p>
                    @endforelse
                </div>
            </div>
        </section>

        <section id="shopping-directory" class="mx-auto max-w-7xl px-4 py-12 sm:px-5 lg:py-16">
            <p class="text-xs font-black uppercase tracking-[0.22em] text-gold">Shopping Directory</p>
            <h2 class="mt-2 text-3xl font-black text-deepRed">Direktori Tenant</h2>

            <div class="mt-6 flex gap-2 overflow-x-auto pb-2">
                <button type="button" onclick="filterTenant('all')" class="shrink-0 rounded-full bg-deepRed px-4 py-2 text-sm font-black text-white">Semua</button>
                @foreach ($kategoriTenants as $kategori)
                    <button type="button" onclick="filterTenant('{{ $kategori->id_kategori }}')" class="shrink-0 rounded-full border border-gold bg-white px-4 py-2 text-sm font-black text-deepRed hover:bg-gold">
                        {{ $kategori->nama_kategori }}
                    </button>
                @endforeach
            </div>

            <div class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @forelse ($tenants as $tenant)
                    <article class="tenant-card group overflow-hidden rounded-lg border border-gold/25 bg-white shadow-lg transition duration-300 hover:-translate-y-1 hover:shadow-xl" data-category="{{ $tenant->id_kategori }}">
                        {{-- Upload tenant memakai public/storage. Jalankan "php artisan storage:link" hanya jika instalasi Anda menyimpan file melalui disk public Laravel standar. --}}
                        <div class="overflow-hidden bg-cream">
                            @if ($tenant->gambar_tenant)
                                <img src="{{ asset('storage/' . $tenant->gambar_tenant) }}" alt="Logo {{ $tenant->nama_tenant }}" class="aspect-video h-48 w-full object-cover transition duration-300 group-hover:scale-105">
                            @else
                                <div class="grid aspect-video h-48 w-full place-items-center bg-deepRed/5 text-deepRed">
                                    <i data-lucide="store" class="h-12 w-12"></i>
                                </div>
                            @endif
                        </div>
                        <div class="p-5">
                            <p class="text-xs font-black uppercase tracking-wide text-gold">{{ $tenant->nama_kategori }}</p>
                            <h3 class="mt-2 text-xl font-black text-deepRed">{{ $tenant->nama_tenant }}</h3>
                            <p class="mt-2 text-sm text-zinc-600">Unit {{ $tenant->no_unit ?? '-' }} · Lantai {{ $tenant->lantai ?? '-' }}</p>
                        </div>
                    </article>
                @empty
                    <p class="text-zinc-600">Tenant aktif belum tersedia.</p>
                @endforelse
            </div>
        </section>

        <section id="rewards-catalog" class="bg-deepRed py-12 text-white lg:py-16">
            <div class="mx-auto max-w-7xl px-4 sm:px-5">
                <p class="text-xs font-black uppercase tracking-[0.22em] text-gold">Rewards Catalog</p>
                <h2 class="mt-2 text-3xl font-black">Tukar Poin Anda</h2>
                <div class="mt-7 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    @forelse ($katalogHadiah as $hadiah)
                        <article class="rounded-lg border border-gold/30 bg-white/10 p-5 backdrop-blur">
                            <div class="grid aspect-[4/3] place-items-center rounded-md bg-cream text-center text-deepRed">
                                @if ($hadiah->gambar_url)
                                    <img src="{{ $hadiah->gambar_url }}" alt="{{ $hadiah->nama_hadiah }}" class="h-full w-full rounded-md object-cover">
                                @else
                                    <span class="px-4 text-lg font-black">Reward</span>
                                @endif
                            </div>
                            <h3 class="mt-4 font-black">{{ $hadiah->nama_hadiah }}</h3>
                            <p class="mt-2 text-sm font-bold text-gold">{{ number_format($hadiah->poin_dibutuhkan, 0, ',', '.') }} Poin</p>
                            <p class="mt-1 text-sm text-white/70">Stok: {{ $hadiah->stok }}</p>
                        </article>
                    @empty
                        <p class="text-white/75">Katalog hadiah belum tersedia.</p>
                    @endforelse
                </div>
            </div>
        </section>

        <section id="contact-us" class="mx-auto max-w-7xl px-4 py-12 sm:px-5 lg:py-16">
            <div class="rounded-lg border border-gold/30 bg-white p-6 shadow-premium md:flex md:items-center md:justify-between">
                <div>
                    <p class="text-xs font-black uppercase tracking-[0.22em] text-gold">Contact Us</p>
                    <h2 class="mt-2 text-3xl font-black text-deepRed">Puri Indah Mall</h2>
                    <p class="mt-3 max-w-2xl text-sm leading-6 text-zinc-700">
                        Kunjungi pusat informasi mall untuk bantuan membership, klaim poin, dan informasi tenant.
                    </p>
                </div>
                <a href="#home" class="mt-6 inline-flex rounded-full bg-deepRed px-6 py-3 font-black text-white hover:bg-gold hover:text-deepRed md:mt-0">Kembali ke Atas</a>
            </div>
        </section>
    </main>

    <footer class="bg-deepRed text-cream">
        <div class="mx-auto grid max-w-7xl gap-10 px-4 py-12 sm:px-5 md:grid-cols-2 lg:grid-cols-4">
            <div>
                <a href="#home" class="flex items-center gap-3">
                    <img src="{{ asset('images/logo-white.png') }}" alt="Logo Puri Indah Mall" class="h-16 w-16 object-contain">
                    <div>
                        <p class="text-xs font-black uppercase tracking-[0.2em] text-gold">Puri Indah</p>
                        <p class="text-lg font-black text-white">Mall Loyalty</p>
                    </div>
                </a>
                <p class="mt-4 text-sm leading-6 text-cream/85">Destinasi belanja, kuliner, hiburan, dan layanan loyalitas member di Jakarta Barat.</p>
                <div class="mt-5 flex gap-3">
                    <a href="https://www.instagram.com/puriindahmall/" target="_blank" rel="noopener noreferrer" aria-label="Instagram Puri Indah Mall" class="grid h-10 w-10 place-items-center rounded-full border border-gold/60 text-gold hover:bg-gold hover:text-deepRed"><i class="fa-brands fa-instagram text-lg"></i></a>
                    <a href="https://www.facebook.com/puriindahmall/" target="_blank" rel="noopener noreferrer" aria-label="Facebook Puri Indah Mall" class="grid h-10 w-10 place-items-center rounded-full border border-gold/60 text-gold hover:bg-gold hover:text-deepRed"><i class="fa-brands fa-facebook-f text-lg"></i></a>
                    <a href="#contact-us" aria-label="Kontak layanan Puri Indah Mall" class="grid h-10 w-10 place-items-center rounded-full border border-gold/60 text-gold hover:bg-gold hover:text-deepRed"><i class="fa-solid fa-envelope text-base"></i></a>
                </div>
            </div>

            <div>
                <h3 class="text-sm font-black uppercase tracking-[0.18em] text-gold">Quick Links</h3>
                <nav class="mt-4 grid gap-3 text-sm font-semibold text-cream/90">
                    <a href="#shopping-directory" class="hover:text-gold">Direktori Tenant</a>
                    <a href="#news-promotion" class="hover:text-gold">Event Aktif</a>
                    <a href="#service" class="hover:text-gold">Fasilitas Mall</a>
                    <a href="#contact-us" class="hover:text-gold">Kontak Layanan Poin</a>
                </nav>
            </div>

            <div>
                <h3 class="text-sm font-black uppercase tracking-[0.18em] text-gold">Jam Operasional</h3>
                <div class="mt-4 space-y-3 text-sm text-cream/90">
                    <p class="flex items-start gap-2"><i data-lucide="clock-3" class="mt-0.5 h-4 w-4 shrink-0 text-gold"></i><span>Setiap hari<br><strong class="text-white">10.00 - 22.00 WIB</strong></span></p>
                    <p class="flex items-start gap-2"><i data-lucide="info" class="mt-0.5 h-4 w-4 shrink-0 text-gold"></i><span>Jam tenant tertentu dapat berbeda mengikuti kebijakan operasional.</span></p>
                </div>
            </div>

            <div>
                <h3 class="text-sm font-black uppercase tracking-[0.18em] text-gold">Lokasi Mall</h3>
                <p class="mt-4 flex items-start gap-2 text-sm leading-6 text-cream/90">
                    <i data-lucide="map-pin" class="mt-1 h-4 w-4 shrink-0 text-gold"></i>
                    <span>Jl. Puri Agung No.1, Kembangan Selatan, Jakarta Barat, DKI Jakarta 11610</span>
                </p>
                <a href="https://maps.google.com/?q=Puri+Indah+Mall" target="_blank" rel="noopener noreferrer" class="mt-4 inline-flex items-center gap-2 rounded-md border border-gold/60 px-4 py-2 text-sm font-black text-gold hover:bg-gold hover:text-deepRed">
                    <i data-lucide="map" class="h-4 w-4"></i>
                    Buka Google Maps
                </a>
            </div>
        </div>

        <div class="bg-wine">
            <div class="mx-auto flex max-w-7xl flex-col gap-2 px-4 py-4 text-xs font-semibold text-cream/80 sm:px-5 md:flex-row md:items-center md:justify-between">
                <p>&copy; {{ date('Y') }} Puri Indah Mall Loyalty System. All rights reserved.</p>
                <div class="flex gap-4"><a href="#contact-us" class="hover:text-gold">Kebijakan Privasi</a><a href="#contact-us" class="hover:text-gold">Syarat Layanan</a></div>
            </div>
        </div>
    </footer>

    <script>
        function filterTenant(category) {
            document.querySelectorAll('.tenant-card').forEach((card) => {
                card.classList.toggle('hidden', category !== 'all' && card.dataset.category !== category);
            });
        }
        lucide.createIcons();
    </script>
</body>
</html>
