<?php

namespace App\Http\Controllers;

use App\Exports\MemberExport;
use App\Exports\TenantExport;
use App\Reports\ManagerReportData;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;

class ManagerReportController extends Controller
{
    public function exportTenantExcel()
    {
        return Excel::download(
            new TenantExport(ManagerReportData::tenantPerformance()),
            'laporan-performa-tenant-' . now()->format('Ymd-His') . '.xlsx'
        );
    }

    public function exportMemberExcel()
    {
        return Excel::download(
            new MemberExport(ManagerReportData::memberLoyalty()),
            'laporan-aktivitas-member-' . now()->format('Ymd-His') . '.xlsx'
        );
    }

    public function exportTenantPdf()
    {
        $rows = ManagerReportData::tenantPerformance();

        return Pdf::loadView('mgmt.reports.tenant_pdf', [
            'rows' => $rows,
            'totals' => $this->tenantTotals($rows),
            'manager' => auth('pegawai')->user(),
            'period' => 'Seluruh data terverifikasi sampai ' . now()->format('d-m-Y'),
            'printedAt' => now()->format('d-m-Y H:i:s'),
            'logoData' => $this->logoDataUri(),
        ])
            ->setPaper('a4', 'landscape')
            ->download('laporan-performa-tenant-' . now()->format('Ymd-His') . '.pdf');
    }

    public function exportMemberPdf()
    {
        $rows = ManagerReportData::memberLoyalty();

        return Pdf::loadView('mgmt.reports.member_pdf', [
            'rows' => $rows,
            'totals' => $this->memberTotals($rows),
            'manager' => auth('pegawai')->user(),
            'period' => 'Seluruh data member sampai ' . now()->format('d-m-Y'),
            'printedAt' => now()->format('d-m-Y H:i:s'),
            'logoData' => $this->logoDataUri(),
        ])
            ->setPaper('a4', 'landscape')
            ->download('laporan-aktivitas-member-' . now()->format('Ymd-His') . '.pdf');
    }

    private function tenantTotals(Collection $rows): array
    {
        return [
            'total_struk' => $rows->sum('total_struk'),
            'total_omzet' => $rows->sum('total_omzet'),
            'total_poin' => $rows->sum('total_poin'),
        ];
    }

    private function memberTotals(Collection $rows): array
    {
        return [
            'lifetime_points' => $rows->sum('lifetime_points'),
            'redeemed_points' => $rows->sum('redeemed_points'),
            'current_points' => $rows->sum('current_points'),
        ];
    }

    private function logoDataUri(): ?string
    {
        foreach ([
            public_path('images/logo-white.png'),
            public_path('images/logo.png'),
            public_path('logo.png'),
        ] as $path) {
            if (is_file($path)) {
                $mime = pathinfo($path, PATHINFO_EXTENSION) === 'jpg' ? 'image/jpeg' : 'image/png';

                return 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($path));
            }
        }

        return null;
    }
}
