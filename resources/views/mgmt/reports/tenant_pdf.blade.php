<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Performa Tenant</title>
    <style>
        @page { margin: 16mm 11mm 17mm; }
        body { font-family: Arial, Helvetica, sans-serif; font-size: 10pt; color: #0f172a; }
        .letterhead { border: 1px solid #CBD5E1; border-radius: 8px; overflow: hidden; margin-bottom: 14px; }
        .head-main { background: #0F172A; color: #fff; padding: 14px 16px; }
        .brand { min-height: 58px; }
        .brand-logo { float: left; width: 72px; }
        .brand-copy { margin-left: 72px; padding-top: 3px; }
        .logo-box { width: 54px; height: 54px; border-radius: 10px; background: #8B0000; color: #D4AF37; text-align: center; line-height: 54px; font-weight: 700; }
        .logo-img { max-width: 58px; max-height: 58px; }
        h1 { margin: 0; font-size: 19pt; letter-spacing: .2px; }
        .subtitle { margin-top: 4px; color: #CBD5E1; font-size: 9pt; }
        .meta { width: 100%; background: #F8FAFC; padding: 10px 16px; box-sizing: border-box; overflow: hidden; }
        .meta-item { float: left; width: 33.333%; font-size: 9pt; }
        .label { color: #64748B; font-size: 8pt; text-transform: uppercase; letter-spacing: .5px; }
        .value { margin-top: 2px; font-weight: 700; color: #0f172a; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #0F172A; color: #fff; border: 1px solid #CBD5E1; padding: 8px; font-size: 8.5pt; text-align: left; }
        td { border: 1px solid #CBD5E1; padding: 8px; font-size: 9pt; }
        tbody tr:nth-child(even) td { background: #F8FAFC; }
        .num { text-align: right; white-space: nowrap; }
        .center { text-align: center; }
        .badge { display: inline-block; padding: 3px 7px; border-radius: 999px; font-weight: 700; font-size: 8pt; }
        .active { background: #DCFCE7; color: #166534; }
        .inactive { background: #FEE2E2; color: #991B1B; }
        .total-row td { background: #FEF3C7; font-weight: 700; }
        .footer-meta { position: fixed; bottom: -10mm; right: 0; color: #64748B; font-size: 8pt; }
        @media print { body { -webkit-print-color-adjust: exact; print-color-adjust: exact; } }
    </style>
</head>
<body>
    <section class="letterhead">
        <div class="head-main">
            <div class="brand">
                <div class="brand-logo">
                    @if ($logoData)
                        <img class="logo-img" src="{{ $logoData }}" alt="Puri Indah Mall">
                    @else
                        <div class="logo-box">PIM</div>
                    @endif
                </div>
                <div class="brand-copy">
                    <h1>Laporan Performa Tenant</h1>
                    <div class="subtitle">Puri Indah Mall Loyalty System - Executive Report</div>
                </div>
            </div>
        </div>
        <div class="meta">
            <div class="meta-item"><div class="label">Periode</div><div class="value">{{ $period }}</div></div>
            <div class="meta-item"><div class="label">Diunduh Oleh</div><div class="value">{{ $manager?->nama_pegawai ?? 'Manager' }}</div></div>
            <div class="meta-item"><div class="label">Waktu Cetak</div><div class="value">{{ $printedAt }}</div></div>
        </div>
    </section>

    <table>
        <thead>
            <tr>
                <th>ID/Kode Tenant</th>
                <th>Nama Tenant</th>
                <th>Kategori</th>
                <th class="center">Total Struk</th>
                <th class="num">Total Omset</th>
                <th class="center">Total Poin</th>
                <th class="center">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($rows as $row)
                <tr>
                    <td>{{ 'TNT-' . str_pad((string) $row->id_tenant, 4, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ $row->nama_tenant }}</td>
                    <td>{{ $row->nama_kategori }}</td>
                    <td class="center">{{ number_format($row->total_struk, 0, ',', '.') }}</td>
                    <td class="num">Rp {{ number_format($row->total_omzet, 0, ',', '.') }}</td>
                    <td class="center">{{ number_format($row->total_poin, 0, ',', '.') }}</td>
                    <td class="center"><span class="badge {{ $row->is_active ? 'active' : 'inactive' }}">{{ $row->is_active ? 'Aktif' : 'Non-aktif' }}</span></td>
                </tr>
            @empty
                <tr><td colspan="7" class="center">Belum ada data tenant.</td></tr>
            @endforelse
            <tr class="total-row">
                <td>TOTAL KESELURUHAN</td>
                <td></td>
                <td></td>
                <td class="center">{{ number_format($totals['total_struk'], 0, ',', '.') }}</td>
                <td class="num">Rp {{ number_format($totals['total_omzet'], 0, ',', '.') }}</td>
                <td class="center">{{ number_format($totals['total_poin'], 0, ',', '.') }}</td>
                <td></td>
            </tr>
        </tbody>
    </table>

    <div class="footer-meta">Dicetak: {{ $printedAt }}</div>
    <script type="text/php">
        if (isset($pdf)) {
            $font = $fontMetrics->getFont("Helvetica", "normal");
            $pdf->page_text(710, 565, "Halaman {PAGE_NUM} dari {PAGE_COUNT}", $font, 8, [0.39, 0.45, 0.55]);
        }
    </script>
</body>
</html>
