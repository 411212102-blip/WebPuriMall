@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('eyebrow', 'Verification Command Center')
@section('page-title', 'Dashboard Operasional')

@section('content')
    @php
        $c1 = $totalClustered > 0 ? round((($clusterCounts[1] ?? 0) / $totalClustered) * 100) : 0;
        $c2 = $totalClustered > 0 ? round((($clusterCounts[2] ?? 0) / $totalClustered) * 100) : 0;
        $c3 = max(0, 100 - $c1 - $c2);
    @endphp

    <div class="grid gap-6">
        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <article class="rounded-xl border border-gold/30 bg-white p-5 shadow-glow">
                <p class="text-sm text-gray-700">Pending Receipts</p>
                <p class="mt-3 text-3xl font-black text-gray-900">{{ $pendingTransaksi->count() }}</p>
            </article>
            <article class="rounded-xl border border-gold/30 bg-white p-5 shadow-glow">
                <p class="text-sm text-gray-700">Active Events</p>
                <p class="mt-3 text-3xl font-black text-deepRed">{{ $activeEvents->count() }}</p>
            </article>
            <article class="rounded-xl border border-gold/30 bg-white p-5 shadow-glow">
                <p class="text-sm text-gray-700">Parking Capacity</p>
                <p class="mt-3 text-3xl font-black text-deepRed">{{ number_format($parkingStats['total_capacity']) }}</p>
            </article>
            <article class="rounded-xl border border-gold/30 bg-white p-5 shadow-glow">
                <p class="text-sm text-gray-700">Redeem Hari Ini</p>
                <p class="mt-3 text-3xl font-black text-red-800">{{ $redeemStats['today'] }}</p>
            </article>
        </section>

        <section class="grid gap-6 xl:grid-cols-[1.45fr_0.55fr]">
            <div class="rounded-xl border border-gold/30 bg-white shadow-glow">
                <div class="flex flex-col gap-4 border-b border-gold/30 px-5 py-4 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <h3 class="text-lg font-black text-gray-900">Riwayat Verifikasi Struk</h3>
                        <!-- <p class="text-sm text-gray-700">Filter status langsung dari kolom `status_transaksi`.</p> -->
                    </div>
                    <div class="flex flex-wrap gap-2">
                        @foreach (['Pending', 'Approved', 'Rejected'] as $item)
                            <a href="{{ route('admin.verifikasi.index', ['status' => $item]) }}"
                               class="rounded-full px-4 py-2 text-sm font-black {{ $status === $item ? 'bg-deepRed text-cream' : 'border border-gold/30 text-gray-800 hover:border-deepRed hover:text-deepRed' }}">
                                {{ $item }}
                            </a>
                        @endforeach
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[1180px] text-left text-sm">
                        <thead class="bg-deepRed/5 text-xs uppercase tracking-wider text-gray-700">
                            <tr>
                                <th class="px-5 py-3">ID</th>
                                <th class="px-5 py-3">Foto</th>
                                <th class="px-5 py-3">Pelanggan</th>
                                <th class="px-5 py-3">Tenant</th>
                                <th class="px-5 py-3">Tanggal</th>
                                <th class="px-5 py-3">Nominal</th>
                                <th class="px-5 py-3">Verifikator</th>
                                <th class="px-5 py-3">Catatan</th>
                                <th class="px-5 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-line">
                            @forelse ($filteredTransaksi as $struk)
                                @php
                                    $fotoUrl = asset('storage/struk/' . basename((string) $struk->foto_struk));
                                    $badge = match ($struk->status_transaksi) {
                                        'Approved' => 'bg-green-50 text-green-800',
                                        'Rejected' => 'bg-red-50 text-red-800',
                                        default => 'bg-yellow-50 text-deepRed',
                                    };
                                @endphp
                                <tr class="hover:bg-cream">
                                    <td class="px-5 py-4 font-black text-deepRed">TRX-{{ $struk->id_transaksi }}</td>
                                    <td class="px-5 py-4">
                                        <button type="button" onclick="previewStruk('{{ $fotoUrl }}')">
                                            <img src="{{ $fotoUrl }}" alt="Foto struk" class="h-20 w-16 rounded-lg border border-gold/30 object-cover hover:border-deepRed">
                                        </button>
                                    </td>
                                    <td class="px-5 py-4 text-gray-900">{{ $struk->pelanggan?->nama_pelanggan ?? '-' }}</td>
                                    <td class="px-5 py-4">{{ $struk->tenant?->nama_tenant ?? '-' }}</td>
                                    <td class="px-5 py-4 text-gray-800">{{ $struk->tanggal_transaksi?->format('Y-m-d H:i') }}</td>
                                    <td class="px-5 py-4 font-bold">Rp {{ number_format((float) $struk->nominal_belanja, 0, ',', '.') }}</td>
                                    <td class="px-5 py-4">{{ $struk->pegawai?->nama_pegawai ?? '-' }}</td>
                                    <td class="px-5 py-4">
                                        <span class="rounded-full px-3 py-1 text-xs font-black {{ $badge }}">{{ $struk->status_transaksi }}</span>
                                        @if ($struk->catatan_tolak)
                                            <p class="mt-2 max-w-xs text-xs text-gray-700">{{ $struk->catatan_tolak }}</p>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4">
                                        @if ($struk->status_transaksi === 'Pending')
                                            <div class="flex justify-end gap-2">
                                                <form method="POST" action="{{ route('admin.transaksi.approve', $struk->id_transaksi) }}">
                                                    @csrf
                                                    <button class="rounded-lg bg-deepRed px-3 py-2 font-black text-cream hover:bg-gold">Approve</button>
                                                </form>
                                                <form method="POST" action="{{ route('admin.transaksi.reject', $struk->id_transaksi) }}" class="flex gap-2">
                                                    @csrf
                                                    <input name="catatan_tolak" required maxlength="1000" placeholder="Alasan reject" class="w-44 rounded-lg border border-gold/30 bg-cream px-3 py-2 text-sm text-gray-900 outline-none focus:border-deepRed">
                                                    <button class="rounded-lg border border-red-700 px-3 py-2 font-black text-red-800 hover:bg-red-700 hover:text-cream">Reject</button>
                                                </form>
                                            </div>
                                        @else
                                            <span class="block text-right text-gray-700">Selesai</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="px-5 py-14 text-center text-gray-700">Tidak ada data {{ $status }}.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="border-t border-gold/30 px-5 py-4">{{ $filteredTransaksi->links() }}</div>
            </div>

            <aside class="rounded-xl border border-gold/30 bg-white p-5 shadow-glow">
                <h3 class="text-lg font-black text-gray-900">K-Means Analytics</h3>
                <!-- <p class="text-sm text-gray-700">Proporsi cluster dari `vw_rfm_pelanggan`.</p> -->
                <div class="mt-7 grid place-items-center">
                    <div class="relative h-52 w-52 rounded-full" style="background: conic-gradient(#fb7185 0 {{ $c1 }}%, #fbbf24 {{ $c1 }}% {{ $c1 + $c2 }}%, #5eead4 {{ $c1 + $c2 }}% 100%);">
                        <div class="absolute inset-8 grid place-items-center rounded-full bg-white text-center">
                            <p class="text-3xl font-black text-gray-900">{{ $totalClustered }}</p>
                            <p class="text-xs uppercase tracking-widest text-gray-700">Members</p>
                        </div>
                    </div>
                </div>
                <div class="mt-7 space-y-3 text-sm">
                    <div class="flex items-center justify-between rounded-lg bg-red-50 px-4 py-3"><span class="font-bold text-red-800">Cluster 1 At-Risk</span><span>{{ $c1 }}%</span></div>
                    <div class="flex items-center justify-between rounded-lg bg-yellow-50 px-4 py-3"><span class="font-bold text-deepRed">Cluster 2 Potensial</span><span>{{ $c2 }}%</span></div>
                    <div class="flex items-center justify-between rounded-lg bg-deepRed/10 px-4 py-3"><span class="font-bold text-deepRed">Cluster 3 Champions</span><span>{{ $c3 }}%</span></div>
                </div>
            </aside>
        </section>

        <section class="grid gap-6 xl:grid-cols-3">
            <div class="rounded-xl border border-gold/30 bg-white p-5 shadow-glow">
                <h3 class="text-lg font-black text-gray-900">Event Aktif</h3>
                <div class="mt-4 grid gap-3">
                    @forelse ($activeEvents as $event)
                        <div class="rounded-xl bg-cream p-4">
                            <p class="font-black text-gray-900">{{ $event->nama_event }}</p>
                            <p class="mt-1 text-sm text-gray-700">{{ $event->lokasi }} � {{ \Illuminate\Support\Carbon::parse($event->tgl_mulai)->format('d M') }} - {{ \Illuminate\Support\Carbon::parse($event->tgl_selesai)->format('d M Y') }}</p>
                        </div>
                    @empty
                        <p class="text-sm text-gray-700">Tidak ada event aktif hari ini.</p>
                    @endforelse
                </div>
            </div>

            <div class="rounded-xl border border-gold/30 bg-white p-5 shadow-glow">
                <h3 class="text-lg font-black text-gray-900">Fasilitas</h3>
                <p class="mt-2 text-3xl font-black text-deepRed">{{ $facilityStats['total'] }}</p>
                <div class="mt-4 grid gap-2">
                    @foreach ($facilityStats['by_floor'] as $floor)
                        <div class="flex justify-between rounded-lg bg-cream px-3 py-2"><span>{{ $floor->lokasi_lantai }}</span><span class="text-deepRed">{{ $floor->total }}</span></div>
                    @endforeach
                </div>
            </div>

            <div class="rounded-xl border border-gold/30 bg-white p-5 shadow-glow">
                <h3 class="text-lg font-black text-gray-900">Parkir & Redeem</h3>
                <div class="mt-4 grid gap-3">
                    <div class="rounded-lg bg-cream p-3"><p class="text-sm text-gray-700">Total Kapasitas</p><p class="text-2xl font-black text-deepRed">{{ number_format($parkingStats['total_capacity']) }}</p></div>
                    <div class="rounded-lg bg-cream p-3"><p class="text-sm text-gray-700">Redeem Hari Ini</p><p class="text-2xl font-black text-red-800">{{ $redeemStats['today'] }}</p></div>
                </div>
            </div>
        </section>
    </div>

    <div id="previewModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/80 p-5" onclick="closePreview()">
        <div class="max-h-[92vh] max-w-4xl" onclick="event.stopPropagation()">
            <button type="button" onclick="closePreview()" class="mb-3 rounded-lg bg-deepRed px-4 py-2 font-black text-cream">Tutup</button>
            <img id="previewImage" src="" alt="Preview foto struk" class="max-h-[85vh] rounded-xl border border-gold/30 object-contain">
        </div>
    </div>
    <script>
        function previewStruk(src) {
            document.getElementById('previewImage').src = src;
            document.getElementById('previewModal').classList.remove('hidden');
            document.getElementById('previewModal').classList.add('flex');
        }
        function closePreview() {
            document.getElementById('previewModal').classList.add('hidden');
            document.getElementById('previewModal').classList.remove('flex');
            document.getElementById('previewImage').src = '';
        }
    </script>
@endsection


