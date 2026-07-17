<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin - Puri Indah Mall')</title>
    <link rel="icon" href="{{ asset('images/logo-white.png') }}" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        cream: '#FDF5E6',
                        deepRed: '#8B0000',
                        wine: '#5F0000',
                        gold: '#D4AF37',
                        line: '#E8D7A6',
                        ink: '#FDF5E6',
                        panel: '#FFFFFF',
                        panel2: '#FFF8EA',
                        teal: '#8B0000',
                        mint: '#D4AF37',
                    },
                    boxShadow: { glow: '0 18px 50px rgba(139, 0, 0, 0.16)' },
                },
            },
        };
    </script>
</head>
<body class="min-h-screen bg-cream text-zinc-900 antialiased">
    @php
        $pegawai = auth('pegawai')->user();
        $role = $pegawai?->role;
        $menus = [
            ['label' => 'Dashboard', 'icon' => 'layout-dashboard', 'route' => $role === 'manager' ? 'mgmt.dashboard' : 'staff.dashboard', 'match' => ['staff.dashboard', 'mgmt.dashboard', 'admin.verifikasi.index'], 'roles' => ['superadmin', 'admin', 'manager', 'kasir']],
            ['label' => 'Klaim Voucher', 'icon' => 'ticket-check', 'route' => 'admin.voucher-claims.index', 'match' => ['admin.voucher-claims.*'], 'roles' => ['superadmin', 'admin', 'kasir']],
            ['label' => 'Riwayat Redeem', 'icon' => 'history', 'route' => 'admin.redeem-history.index', 'match' => ['admin.redeem-history.*'], 'roles' => ['superadmin', 'admin', 'manager']],
            ['label' => 'Data Pelanggan', 'icon' => 'users', 'route' => 'admin.pelanggan.index', 'match' => ['admin.pelanggan.*'], 'roles' => ['superadmin', 'admin', 'manager']],
            ['label' => 'Master Tenant', 'icon' => 'store', 'route' => 'admin.tenants.index', 'match' => ['admin.tenants.*'], 'roles' => ['superadmin', 'admin']],
            ['label' => 'Event Mall', 'icon' => 'calendar-days', 'route' => 'admin.events.index', 'match' => ['admin.events.*'], 'roles' => ['superadmin', 'admin']],
            ['label' => 'Fasilitas', 'icon' => 'building-2', 'route' => 'admin.fasilitas.index', 'match' => ['admin.fasilitas.*'], 'roles' => ['superadmin', 'admin']],
            ['label' => 'Katalog Hadiah', 'icon' => 'gift', 'route' => 'admin.hadiah.index', 'match' => ['admin.hadiah.*'], 'roles' => ['superadmin', 'admin']],
        ];
    @endphp

    <div class="flex min-h-screen">
        <aside class="fixed inset-y-0 left-0 z-40 hidden w-72 border-r border-gold/30 bg-deepRed px-5 py-5 text-cream shadow-glow lg:block">
            <a href="{{ route('home') }}" class="flex items-center gap-4">
                <img src="{{ asset('images/logo-white.png') }}" alt="Logo" class="h-16 w-16 rounded-2xl object-contain p-1.5">
                <div>
                    <p class="text-[11px] font-black uppercase tracking-[0.28em] text-gold">Puri Indah Mall</p>
                    <h1 class="mt-1 text-lg font-black leading-tight text-white">Loyalty Admin</h1>
                </div>
            </a>

            <div class="mt-7 rounded-xl border border-gold/35 bg-wine/70 p-4">
                <p class="text-xs font-bold uppercase tracking-widest text-gold">Signed in</p>
                <p class="mt-2 font-black text-white">{{ $pegawai?->nama_pegawai ?? 'Pegawai' }}</p>
                <p class="mt-1 text-sm text-cream/80">{{ strtoupper($role ?? '-') }}</p>
            </div>

            <nav class="mt-7 grid gap-2 text-sm font-bold">
                @foreach ($menus as $menu)
                    @if (in_array($role, $menu['roles'], true))
                        @php $active = request()->routeIs(...$menu['match']); @endphp
                        <a href="{{ route($menu['route']) }}"
                           class="flex items-center gap-3 rounded-lg px-4 py-3 transition {{ $active ? 'bg-gold text-deepRed shadow' : 'text-cream/85 hover:bg-wine hover:text-gold' }}">
                            <i data-lucide="{{ $menu['icon'] }}" class="h-5 w-5"></i>
                            <span>{{ $menu['label'] }}</span>
                        </a>
                    @endif
                @endforeach
            </nav>

            <form method="POST" action="{{ route('logout') }}" class="absolute bottom-5 left-5 right-5">
                @csrf
                <button class="w-full rounded-xl bg-gold px-4 py-3 font-black text-deepRed transition hover:bg-cream">Logout</button>
            </form>
        </aside>

        <div class="flex min-w-0 flex-1 flex-col lg:pl-72">
            <header class="sticky top-0 z-30 border-b border-gold/30 bg-white/90 backdrop-blur">
                <div class="flex items-center justify-between gap-3 px-4 py-4 sm:px-6">
                    <div>
                        <p class="text-xs font-black uppercase tracking-[0.24em] text-gold">@yield('eyebrow', 'Puri Indah Mall')</p>
                        <h2 class="text-xl font-black text-deepRed sm:text-2xl">@yield('page-title', 'Dashboard')</h2>
                    </div>
                    <a href="{{ route('home') }}" class="rounded-full border border-gold px-4 py-2 text-sm font-bold text-deepRed hover:bg-gold">Public Site</a>
                </div>
            </header>

            <main class="px-4 py-6 sm:px-6 xl:px-8">
                @if (session('success'))
                    <div class="mb-5 rounded-xl border border-green-200 bg-green-50 px-5 py-4 text-sm font-bold text-green-800">{{ session('success') }}</div>
                @endif
                @if ($errors->any())
                    <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-sm font-bold text-red-800">{{ $errors->first() }}</div>
                @endif
                @yield('content')
            </main>
        </div>
    </div>
    <script>
        lucide.createIcons();
    </script>
</body>
</html>
