<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Puri Indah Mall</title>
    <link rel="icon" href="{{ asset('images/logo-white.png') }}" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { cream: '#FDF5E6', deepRed: '#8B0000', gold: '#D4AF37' },
                    boxShadow: { premium: '0 20px 50px rgba(139, 0, 0, 0.22)' },
                },
            },
        };
    </script>
</head>
<body class="bg-cream text-zinc-900 antialiased">
    <header class="sticky top-0 z-50 border-b border-red-950/20 bg-deepRed text-white shadow-lg">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-5 py-4">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <!-- <div class="grid h-12 w-12 place-items-center rounded-full border border-gold bg-white text-lg font-black text-deepRed">PM</div> -->
                <img src="{{ asset('images/logo-white.png') }}" alt="Logo" class="h-11 w-11 rounded-xl bg-white object-contain p-1.5">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-gold">Puri Indah</p>
                    <h1 class="text-lg font-bold leading-tight">Mall</h1>
                </div>
            </a>

            <nav class="hidden items-center gap-7 text-xs font-bold uppercase tracking-wide md:flex">
                <a href="#home" class="hover:text-gold">Home</a>
                <a href="#promo" class="hover:text-gold">News & Promotion</a>
                <a href="#service" class="hover:text-gold">Service</a>
                <a href="#directory" class="hover:text-gold">Shopping Directory</a>
                <a href="#contact" class="hover:text-gold">Contact Us</a>
            </nav>

            <div class="flex items-center gap-2 text-sm font-bold">
                <a href="{{ route('login') }}" class="rounded-full border border-gold px-4 py-2 text-gold transition hover:bg-gold hover:text-deepRed">
                    Login Member
                </a>
                <a href="{{ route('register') }}" class="rounded-full bg-gold px-4 py-2 text-deepRed transition hover:bg-white">
                    Daftar Member
                </a>
            </div>
        </div>
    </header>

    <main id="home">
        <section class="relative min-h-[520px] overflow-hidden bg-deepRed">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_15%_20%,rgba(255,255,255,0.28),transparent_23%),linear-gradient(120deg,#b00012_0%,#e11d48_45%,#8B0000_100%)]"></div>
            <div class="relative mx-auto grid max-w-7xl gap-8 px-5 py-16 md:grid-cols-[1.1fr_0.9fr] md:items-center">
                <div class="text-white">
                    <p class="text-sm font-bold uppercase tracking-[0.24em] text-gold">Shop More, Dine More</p>
                    <h2 class="mt-5 max-w-3xl text-5xl font-black leading-tight md:text-7xl">Puri Indah Mall Loyalty</h2>
                    <p class="mt-5 max-w-xl text-lg text-white/85">
                        Nikmati promo tenant, kumpulkan poin dari struk belanja, dan tukarkan reward eksklusif.
                    </p>
                    <div class="mt-8 flex flex-wrap gap-3">
                        <a href="{{ route('register') }}" class="rounded-full bg-gold px-6 py-3 font-black text-deepRed shadow-premium transition hover:bg-white">Daftar Member</a>
                        <a href="{{ route('login') }}" class="rounded-full border border-white/70 px-6 py-3 font-black text-white transition hover:border-gold hover:text-gold">Login Member</a>
                    </div>
                </div>

                <div class="rounded-lg border border-gold/50 bg-white/12 p-4 shadow-premium backdrop-blur">
                    <div class="rounded-md bg-cream p-5 text-deepRed">
                        <p class="text-center text-sm font-black uppercase tracking-[0.2em]">Tenant Participants</p>
                        <div class="mt-4 grid gap-4 sm:grid-cols-2">
                            <div class="rounded-md bg-white p-5 text-center shadow"><p class="text-lg font-black">BABOCHHKAA</p></div>
                            <div class="rounded-md bg-white p-5 text-center shadow"><p class="text-lg font-black">FOEK LAM</p></div>
                            <div class="rounded-md bg-white p-5 text-center shadow sm:col-span-2"><p class="text-lg font-black">ROEMAH KOFFIE</p></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="promo" class="mx-auto max-w-7xl px-5 py-12">
            <div class="flex items-end gap-4">
                <div>
                    <h3 class="text-3xl font-black italic text-deepRed">WHAT'S HOT</h3>
                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-gold">At Puri Indah Mall</p>
                </div>
                <div class="mb-5 h-1 flex-1 bg-deepRed"></div>
            </div>
            <div class="mt-7 grid gap-5 md:grid-cols-3">
                <article class="rounded-lg bg-white p-6 shadow">Shop & Dine Voucher</article>
                <article class="rounded-lg bg-white p-6 shadow">Member Reward</article>
                <article class="rounded-lg bg-white p-6 shadow">Tenant Deals</article>
            </div>
        </section>
    </main>
</body>
</html>
