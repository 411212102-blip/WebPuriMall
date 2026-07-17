<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('images/logo-white.png') }}" type="image/x-icon">
    <title>@yield('title', 'Puri Indah Mall Loyalty')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { cream: '#FDF5E6', deepRed: '#8B0000', wine: '#5F0000', gold: '#D4AF37' },
                    boxShadow: { premium: '0 18px 45px rgba(139, 0, 0, 0.14)' },
                },
            },
        };
    </script>
</head>
<body class="min-h-screen bg-cream text-zinc-900 antialiased">
    @php
        $pelanggan = auth('pelanggan')->user();
        $menus = [
            ['label' => 'Dashboard', 'icon' => 'layout-dashboard', 'route' => 'pelanggan.dashboard', 'match' => ['pelanggan.dashboard']],
            ['label' => 'Upload Struk', 'icon' => 'receipt-text', 'route' => 'pelanggan.upload-struk', 'match' => ['pelanggan.upload-struk*']],
            ['label' => 'Rewards', 'icon' => 'gift', 'route' => 'pelanggan.rewards.index', 'match' => ['pelanggan.rewards.*']],
            ['label' => 'Hadiah Saya', 'icon' => 'ticket-check', 'route' => 'pelanggan.my-rewards.index', 'match' => ['pelanggan.my-rewards.*']],
            ['label' => 'Profil Saya', 'icon' => 'user-round', 'route' => 'pelanggan.profile.edit', 'match' => ['pelanggan.profile.*']],
        ];
    @endphp

    <div class="min-h-screen">
        <aside id="customer-sidebar" class="fixed inset-y-0 left-0 z-50 hidden w-72 border-r border-gold/35 bg-deepRed px-5 py-5 text-cream shadow-premium lg:block">
            <div class="flex items-center justify-between">
                <a href="{{ route('home') }}" class="flex items-center gap-4">
                    <img src="{{ asset('images/logo-white.png') }}" alt="Logo Puri Indah Mall" class="h-16 w-16 rounded-xl object-contain p-1">
                    <div>
                        <p class="text-[11px] font-black uppercase tracking-[0.22em] text-gold">Puri Indah Mall</p>
                        <h1 class="mt-1 text-lg font-black leading-tight text-white">Loyalty Member</h1>
                    </div>
                </a>
                <button type="button" data-close-sidebar class="rounded-md p-2 text-cream hover:bg-wine lg:hidden" aria-label="Tutup navigasi">
                    <i data-lucide="x" class="h-5 w-5"></i>
                </button>
            </div>

            <div class="mt-7 rounded-lg border border-gold/35 bg-wine/70 p-4">
                <p class="text-xs font-bold uppercase tracking-widest text-gold">Member Aktif</p>
                <p class="mt-2 font-black text-white">{{ $pelanggan?->nama_pelanggan ?? 'Pelanggan' }}</p>
                <p class="mt-1 text-sm text-cream/85">{{ $pelanggan?->no_pelanggan ?? 'Nomor member diproses' }}</p>
                <p class="mt-3 text-sm font-black text-gold">{{ number_format($pelanggan?->total_poin ?? 0, 0, ',', '.') }} Poin</p>
            </div>

            <nav class="mt-7 grid gap-2 text-sm font-bold">
                @foreach ($menus as $menu)
                    @php $active = request()->routeIs(...$menu['match']); @endphp
                    <a href="{{ route($menu['route']) }}" class="flex items-center gap-3 rounded-lg px-4 py-3 transition {{ $active ? 'bg-gold text-deepRed shadow' : 'text-cream/90 hover:bg-wine hover:text-gold' }}">
                        <i data-lucide="{{ $menu['icon'] }}" class="h-5 w-5"></i>
                        <span>{{ $menu['label'] }}</span>
                    </a>
                @endforeach
            </nav>

            <div class="absolute bottom-5 left-5 right-5 grid gap-2">
                <a href="{{ route('home') }}" class="flex items-center justify-center gap-2 rounded-lg border border-gold/50 px-4 py-3 text-sm font-black text-gold hover:bg-wine">
                    <i data-lucide="house" class="h-4 w-4"></i>
                    Halaman Utama
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="flex w-full items-center justify-center gap-2 rounded-lg bg-gold px-4 py-3 text-sm font-black text-deepRed hover:bg-cream">
                        <i data-lucide="log-out" class="h-4 w-4"></i>
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <div class="min-h-screen lg:pl-72">
            <header class="sticky top-0 z-30 border-b border-gold/30 bg-white/95 px-4 py-3 shadow-sm lg:hidden">
                <div class="flex items-center justify-between gap-3">
                    <a href="{{ route('home') }}" class="flex items-center gap-3">
                        <img src="{{ asset('images/logo-white.png') }}" alt="Logo" class="h-10 w-10 rounded-lg bg-deepRed object-contain p-2">
                        <p class="font-black text-deepRed">Puri Indah Mall</p>
                    </a>
                    <button type="button" data-open-sidebar class="rounded-md bg-deepRed p-2.5 text-cream" aria-label="Buka navigasi">
                        <i data-lucide="menu" class="h-5 w-5"></i>
                    </button>
                </div>
            </header>

            <main class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                @if (session('success'))
                    <div class="mb-5 rounded-lg border border-green-200 bg-green-50 px-5 py-4 text-sm font-bold text-green-800">{{ session('success') }}</div>
                @endif
                @if ($errors->any())
                    <div class="mb-5 rounded-lg border border-red-200 bg-red-50 px-5 py-4 text-sm font-bold text-red-800">{{ $errors->first() }}</div>
                @endif
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        const sidebar = document.getElementById('customer-sidebar');
        document.querySelector('[data-open-sidebar]')?.addEventListener('click', () => sidebar.classList.remove('hidden'));
        document.querySelector('[data-close-sidebar]')?.addEventListener('click', () => sidebar.classList.add('hidden'));
        lucide.createIcons();
    </script>
</body>
</html>
