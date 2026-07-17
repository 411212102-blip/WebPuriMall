<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Puri Indah Mall</title>
    <link rel="icon" href="{{ asset('images/logo-white.png') }}" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { theme: { extend: { colors: { cream: '#FDF5E6', deepRed: '#8B0000', gold: '#D4AF37' } } } };
    </script>
</head>
<body class="grid min-h-screen place-items-center bg-cream px-5 text-zinc-900">
    <form method="POST" action="{{ route('login.store') }}" class="w-full max-w-md rounded-lg border border-gold/50 bg-white/80 p-7 shadow-xl">
        @csrf
        <div class="mb-7 text-center">
            <div class="mx-auto mb-3 grid h-14 w-14 place-items-center rounded-full bg-deepRed text-xl font-black text-gold">
                <img src="{{ asset('images/logo-white.png') }}" alt="Logo Puri Indah Mall" class="h-8 w-8">
            </div>
            <h1 class="text-2xl font-bold text-deepRed">Puri Indah Mall</h1>
            <p class="mt-1 text-sm text-zinc-600">Loyalty System</p>
        </div>

        <label class="mb-2 block text-sm font-semibold text-deepRed">Email</label>
        <input name="email" type="email" value="{{ old('email') }}" required autofocus
               class="mb-4 w-full rounded-md border border-zinc-300 px-4 py-3 outline-none focus:border-gold focus:ring-2 focus:ring-gold/30">

        <label class="mb-2 block text-sm font-semibold text-deepRed">Password</label>
        <input name="password" type="password" required
               class="mb-4 w-full rounded-md border border-zinc-300 px-4 py-3 outline-none focus:border-gold focus:ring-2 focus:ring-gold/30">

        @error('email')
            <p class="mb-4 rounded-md bg-red-50 px-4 py-3 text-sm text-red-700">{{ $message }}</p>
        @enderror

        <button class="w-full rounded-md bg-deepRed px-4 py-3 font-bold text-cream transition hover:bg-gold hover:text-deepRed">
            Login
        </button>

        <p class="mt-5 text-center text-sm text-zinc-600">
            Belum punya akun?
            <a href="{{ route('register') }}" class="font-bold text-deepRed hover:text-gold">Daftar Member</a>
        </p>
    </form>
</body>
</html>
