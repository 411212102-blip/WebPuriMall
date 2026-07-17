<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar Member - Puri Indah Mall</title>
    <link rel="icon" href="{{ asset('images/logo-white.png') }}" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { theme: { extend: { colors: { cream: '#FDF5E6', deepRed: '#8B0000', gold: '#D4AF37' } } } };
    </script>
</head>
<body class="grid min-h-screen place-items-center bg-cream px-5 py-8 text-zinc-900">
    <form method="POST" action="{{ route('register.store') }}" class="w-full max-w-xl rounded-lg border border-gold/50 bg-white/85 p-7 shadow-xl">
        @csrf
        <div class="mb-7 text-center">
            <div class="mx-auto mb-3 grid h-14 w-14 place-items-center rounded-full bg-deepRed text-xl font-black text-gold">
                <img src="{{ asset('images/logo-white.png') }}" alt="Logo Puri Indah Mall" class="h-8 w-8">
            </div>
            <h1 class="text-2xl font-bold text-deepRed">Daftar Member</h1>
            <p class="mt-1 text-sm text-zinc-600">Puri Indah Mall Loyalty System</p>
        </div>

        <div class="grid gap-4 md:grid-cols-2">
            <div class="md:col-span-2">
                <label class="mb-2 block text-sm font-semibold text-deepRed">Nama Lengkap</label>
                <input name="nama_pelanggan" value="{{ old('nama_pelanggan') }}" required class="w-full rounded-md border border-zinc-300 px-4 py-3 outline-none focus:border-gold focus:ring-2 focus:ring-gold/30">
                @error('nama_pelanggan') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold text-deepRed">Email</label>
                <input name="email_pelanggan" type="email" value="{{ old('email_pelanggan') }}" required class="w-full rounded-md border border-zinc-300 px-4 py-3 outline-none focus:border-gold focus:ring-2 focus:ring-gold/30">
                @error('email_pelanggan') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold text-deepRed">No. WhatsApp</label>
                <input name="no_whatsapp_pelanggan" value="{{ old('no_whatsapp_pelanggan') }}" required class="w-full rounded-md border border-zinc-300 px-4 py-3 outline-none focus:border-gold focus:ring-2 focus:ring-gold/30">
                @error('no_whatsapp_pelanggan') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-2">
                <label class="mb-2 block text-sm font-semibold text-deepRed">NIK KTP</label>
                <input name="no_ktp_pelanggan" value="{{ old('no_ktp_pelanggan') }}" maxlength="16" required class="w-full rounded-md border border-zinc-300 px-4 py-3 outline-none focus:border-gold focus:ring-2 focus:ring-gold/30">
                @error('no_ktp_pelanggan') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-2">
                <label class="mb-2 block text-sm font-semibold text-deepRed">Alamat Lengkap</label>
                <textarea name="alamat_pelanggan" rows="3" maxlength="255" required class="w-full rounded-md border border-zinc-300 px-4 py-3 outline-none focus:border-gold focus:ring-2 focus:ring-gold/30">{{ old('alamat_pelanggan') }}</textarea>
                @error('alamat_pelanggan') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold text-deepRed">Password</label>
                <input name="password" type="password" required class="w-full rounded-md border border-zinc-300 px-4 py-3 outline-none focus:border-gold focus:ring-2 focus:ring-gold/30">
                @error('password') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold text-deepRed">Konfirmasi Password</label>
                <input name="password_confirmation" type="password" required class="w-full rounded-md border border-zinc-300 px-4 py-3 outline-none focus:border-gold focus:ring-2 focus:ring-gold/30">
            </div>
        </div>

        <button class="mt-6 w-full rounded-md bg-deepRed px-4 py-3 font-bold text-cream transition hover:bg-gold hover:text-deepRed">
            Daftar Member
        </button>

        <p class="mt-5 text-center text-sm text-zinc-600">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="font-bold text-deepRed hover:text-gold">Login Member</a>
        </p>
    </form>
</body>
</html>
