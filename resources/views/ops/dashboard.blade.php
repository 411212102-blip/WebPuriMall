<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Operations Dashboard - Puri Indah Mall</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        ink: '#071316',
                        panel: '#0D1F23',
                        line: '#17343A',
                        teal: '#14B8A6',
                        mint: '#5EEAD4',
                    },
                    boxShadow: {
                        glow: '0 18px 60px rgba(20, 184, 166, 0.16)',
                    },
                },
            },
        };
    </script>
</head>
<body class="min-h-screen bg-ink text-slate-100 antialiased">
    <header class="border-b border-line bg-panel/95">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-5 py-4">
            <div>
                <p class="text-xs font-black uppercase tracking-[0.24em] text-teal">Puri Indah Mall</p>
                <h1 class="text-xl font-black">Verification & K-Means Analytics</h1>
            </div>
            <div class="flex items-center gap-3 text-sm">
                <span class="rounded-full border border-teal/40 px-4 py-2 text-mint">Live Internal Dashboard</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="rounded-full bg-teal px-4 py-2 font-black text-ink hover:bg-mint">Logout</button>
                </form>
            </div>
        </div>
    </header>

    <main class="mx-auto grid max-w-7xl gap-6 px-5 py-7">
        <section class="grid gap-4 md:grid-cols-4">
            <article class="rounded-lg border border-line bg-panel p-5 shadow-glow">
                <p class="text-sm text-slate-400">Pending Receipts</p>
                <p class="mt-2 text-3xl font-black text-white">{{ $pendingTransaksi->count() }}</p>
            </article>
            <article class="rounded-lg border border-line bg-panel p-5 shadow-glow">
                <p class="text-sm text-slate-400">Cluster 1 At-Risk</p>
                <p class="mt-2 text-3xl font-black text-rose-300">{{ $clusterCounts[1] ?? 0 }}</p>
            </article>
            <article class="rounded-lg border border-line bg-panel p-5 shadow-glow">
                <p class="text-sm text-slate-400">Cluster 2 Potensial</p>
                <p class="mt-2 text-3xl font-black text-amber-200">{{ $clusterCounts[2] ?? 0 }}</p>
            </article>
            <article class="rounded-lg border border-line bg-panel p-5 shadow-glow">
                <p class="text-sm text-slate-400">Cluster 3 Champions</p>
                <p class="mt-2 text-3xl font-black text-mint">{{ $clusterCounts[3] ?? 0 }}</p>
            </article>
        </section>

        @if (session('success'))
            <div class="rounded-lg border border-teal/40 bg-teal/10 px-5 py-4 text-sm font-bold text-mint">
                {{ session('success') }}
            </div>
        @endif

        <section class="grid gap-6 xl:grid-cols-[1.35fr_0.65fr]">
            <div class="rounded-lg border border-line bg-panel shadow-glow">
                <div class="flex items-center justify-between border-b border-line px-5 py-4">
                    <div>
                        <h2 class="text-lg font-black">Antrean Verifikasi Struk</h2>
                        <p class="text-sm text-slate-400">Data OCR masih berstatus Pending dan menunggu validasi staf.</p>
                    </div>
                    <span class="rounded-full bg-teal/10 px-3 py-1 text-sm font-bold text-mint">Agent 1 OCR Queue</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[860px] text-left text-sm">
                        <thead class="bg-teal/5 text-xs uppercase tracking-wider text-slate-400">
                            <tr>
                                <th class="px-5 py-3">ID Struk</th>
                                <th class="px-5 py-3">Nama Pelanggan</th>
                                <th class="px-5 py-3">Tenant OCR</th>
                                <th class="px-5 py-3">Tanggal OCR</th>
                                <th class="px-5 py-3">Nominal</th>
                                <th class="px-5 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-line">
                            @forelse ($pendingTransaksi as $trx)
                                <tr class="hover:bg-teal/5">
                                    <td class="px-5 py-4 font-black text-mint">TRX-{{ $trx->id_transaksi }}</td>
                                    <td class="px-5 py-4 text-white">{{ $trx->pelanggan?->nama_pelanggan ?? '-' }}</td>
                                    <td class="px-5 py-4">{{ $trx->tenant?->nama_tenant ?? '-' }}</td>
                                    <td class="px-5 py-4 text-slate-300">{{ $trx->tanggal_transaksi?->format('Y-m-d H:i') }}</td>
                                    <td class="px-5 py-4 font-bold">Rp {{ number_format((float) $trx->nominal_belanja, 0, ',', '.') }}</td>
                                    <td class="px-5 py-4">
                                        <div class="flex justify-end gap-2">
                                            <form method="POST" action="{{ route('admin.transaksi.approve', $trx->id_transaksi) }}">
                                                @csrf
                                                <button class="rounded-md bg-teal px-3 py-2 font-black text-ink hover:bg-mint">Approve</button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.transaksi.reject', $trx->id_transaksi) }}">
                                                @csrf
                                                <button class="rounded-md border border-rose-400/50 px-3 py-2 font-black text-rose-300 hover:bg-rose-400 hover:text-ink">Reject</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-5 py-10 text-center text-slate-400">
                                        Tidak ada antrean struk baru
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <aside class="rounded-lg border border-line bg-panel p-5 shadow-glow">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-black">K-Means Analytics</h2>
                        <p class="text-sm text-slate-400">Proporsi segmen pelanggan RFM.</p>
                    </div>
                    <button class="rounded-md bg-teal px-3 py-2 text-sm font-black text-ink hover:bg-mint">Run</button>
                </div>

                <div class="mt-7 grid place-items-center">
                    @php
                        $c1 = $totalClustered > 0 ? round((($clusterCounts[1] ?? 0) / $totalClustered) * 100) : 0;
                        $c2 = $totalClustered > 0 ? round((($clusterCounts[2] ?? 0) / $totalClustered) * 100) : 0;
                        $c3 = max(0, 100 - $c1 - $c2);
                    @endphp
                    <div class="relative h-56 w-56 rounded-full"
                         style="background: conic-gradient(#fb7185 0 {{ $c1 }}%, #fbbf24 {{ $c1 }}% {{ $c1 + $c2 }}%, #5eead4 {{ $c1 + $c2 }}% 100%);">
                        <div class="absolute inset-8 grid place-items-center rounded-full bg-panel text-center">
                            <p class="text-3xl font-black text-white">{{ $totalClustered }}</p>
                            <p class="text-xs uppercase tracking-widest text-slate-400">Members</p>
                        </div>
                    </div>
                </div>

                <div class="mt-7 space-y-3 text-sm">
                    <div class="flex items-center justify-between rounded-md bg-rose-400/10 px-4 py-3">
                        <span class="font-bold text-rose-300">Cluster 1 At-Risk</span>
                        <span>{{ $c1 }}%</span>
                    </div>
                    <div class="flex items-center justify-between rounded-md bg-amber-300/10 px-4 py-3">
                        <span class="font-bold text-amber-200">Cluster 2 Potensial</span>
                        <span>{{ $c2 }}%</span>
                    </div>
                    <div class="flex items-center justify-between rounded-md bg-teal/10 px-4 py-3">
                        <span class="font-bold text-mint">Cluster 3 Champions</span>
                        <span>{{ $c3 }}%</span>
                    </div>
                </div>
            </aside>
        </section>
    </main>
</body>
</html>
