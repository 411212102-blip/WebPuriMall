@extends('layouts.admin')

@section('title', 'Executive Analytics')
@section('eyebrow', 'Executive Intelligence')
@section('page-title', 'Dashboard Manajemen')

@section('content')
    <section class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h3 class="text-2xl font-black text-deepRed">Executive Summary</h3>
            <p class="mt-1 text-sm text-gray-700">Kinerja loyalitas, transaksi tenant, dan segmentasi member mall.</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('mgmt.reports.tenants.xlsx') }}" class="inline-flex items-center gap-2 rounded-md bg-deepRed px-4 py-3 text-sm font-black text-cream hover:bg-gold hover:text-deepRed">
                <i data-lucide="file-spreadsheet" class="h-4 w-4"></i>
                Tenant XLSX
            </a>
            <a href="{{ route('mgmt.reports.tenants.pdf') }}" class="inline-flex items-center gap-2 rounded-md border border-deepRed bg-white px-4 py-3 text-sm font-black text-deepRed hover:bg-deepRed hover:text-cream">
                <i data-lucide="file-text" class="h-4 w-4"></i>
                Tenant PDF
            </a>
            <a href="{{ route('mgmt.reports.members.xlsx') }}" class="inline-flex items-center gap-2 rounded-md border border-gold bg-white px-4 py-3 text-sm font-black text-deepRed hover:bg-gold">
                <i data-lucide="download" class="h-4 w-4"></i>
                Member XLSX
            </a>
            <a href="{{ route('mgmt.reports.members.pdf') }}" class="inline-flex items-center gap-2 rounded-md border border-gold bg-white px-4 py-3 text-sm font-black text-deepRed hover:bg-gold">
                <i data-lucide="file-text" class="h-4 w-4"></i>
                Member PDF
            </a>
        </div>
    </section>

    <section class="mt-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        @foreach ([
            ['label' => 'Pendapatan Hari Ini', 'value' => 'Rp ' . number_format($summary['daily_revenue'], 0, ',', '.'), 'icon' => 'banknote'],
            ['label' => 'Total Member Aktif', 'value' => number_format($summary['total_members']), 'icon' => 'users'],
            ['label' => 'Struk Approved', 'value' => number_format($summary['approved_receipts']), 'icon' => 'receipt-check'],
            ['label' => 'Total Redeem', 'value' => number_format($summary['redeem_count']), 'icon' => 'gift'],
        ] as $card)
            <article class="rounded-lg border border-gold/30 bg-white p-5 shadow">
                <div class="flex items-center justify-between gap-3">
                    <p class="text-sm font-bold text-gray-700">{{ $card['label'] }}</p>
                    <i data-lucide="{{ $card['icon'] }}" class="h-5 w-5 text-deepRed"></i>
                </div>
                <p class="mt-4 text-2xl font-black text-deepRed">{{ $card['value'] }}</p>
            </article>
        @endforeach
    </section>

    <section class="mt-6 grid gap-6 xl:grid-cols-[1.4fr_0.8fr]">
        <article class="rounded-lg border border-gold/30 bg-white p-5 shadow">
            <h3 class="font-black text-gray-900">Tren Upload Struk Mingguan</h3>
            <p class="mt-1 text-sm text-gray-700">Jumlah klaim struk masuk selama tujuh hari terakhir.</p>
            <div class="mt-5 h-72"><canvas id="weeklyChart"></canvas></div>
        </article>

        <article class="rounded-lg border border-gold/30 bg-white p-5 shadow">
            <h3 class="font-black text-gray-900">Distribusi Cluster K-Means</h3>
            <p class="mt-1 text-sm text-gray-700">Segmentasi berdasarkan RFM transaksi approved.</p>
            <div class="mx-auto mt-5 h-64 max-w-sm"><canvas id="clusterChart"></canvas></div>
        </article>
    </section>

    <section class="mt-6 grid gap-6 xl:grid-cols-[0.8fr_1.4fr]">
        <article class="rounded-lg border border-gold/30 bg-white p-5 shadow">
            <h3 class="font-black text-gray-900">Efektivitas Poin Loyalty</h3>
            <p class="mt-1 text-sm text-gray-700">Poin didistribusikan dibandingkan dengan poin yang ditukarkan.</p>
            <div class="mt-5 h-64"><canvas id="loyaltyChart"></canvas></div>
        </article>

        <article class="rounded-lg border border-gold/30 bg-white shadow">
            <div class="border-b border-gold/30 px-5 py-4">
                <h3 class="font-black text-gray-900">Performa Tenant</h3>
                <p class="mt-1 text-sm text-gray-700">Peringkat tenant berdasarkan transaksi approved.</p>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full min-w-[560px] text-left text-sm">
                    <thead class="bg-deepRed/5 text-xs uppercase tracking-wide text-gray-700">
                        <tr><th class="px-5 py-3">Tenant</th><th class="px-5 py-3">Transaksi</th><th class="px-5 py-3">Pendapatan</th></tr>
                    </thead>
                    <tbody class="divide-y divide-gold/20 text-gray-900">
                        @forelse ($topTenants as $tenant)
                            <tr>
                                <td class="px-5 py-4 font-bold">{{ $tenant->nama_tenant }}</td>
                                <td class="px-5 py-4">{{ number_format($tenant->total_transaksi) }}</td>
                                <td class="px-5 py-4 font-black text-deepRed">Rp {{ number_format($tenant->revenue, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="px-5 py-8 text-center text-gray-700">Belum ada transaksi approved.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </article>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const chartDefaults = { responsive: true, maintainAspectRatio: false };
        new Chart(document.getElementById('weeklyChart'), {
            type: 'line',
            data: {
                labels: @json($weeklyTrend->pluck('label')),
                datasets: [{ label: 'Upload Struk', data: @json($weeklyTrend->pluck('total')), borderColor: '#8B0000', backgroundColor: 'rgba(212,175,55,.22)', fill: true, tension: .35 }]
            },
            options: chartDefaults
        });
        new Chart(document.getElementById('clusterChart'), {
            type: 'doughnut',
            data: {
                labels: @json($clusterDistribution->pluck('nama_cluster')),
                datasets: [{ data: @json($clusterDistribution->pluck('total')), backgroundColor: ['#8B0000', '#D4AF37', '#64748B'] }]
            },
            options: chartDefaults
        });
        new Chart(document.getElementById('loyaltyChart'), {
            type: 'bar',
            data: {
                labels: ['Didistribusikan', 'Ditukarkan'],
                datasets: [{ label: 'Poin', data: [{{ $loyalty['distributed'] }}, {{ $loyalty['redeemed'] }}], backgroundColor: ['#8B0000', '#D4AF37'] }]
            },
            options: chartDefaults
        });
    </script>
@endsection
