<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithProperties;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TenantExport implements FromCollection, ShouldAutoSize, WithColumnFormatting, WithEvents, WithHeadings, WithMapping, WithProperties, WithStyles
{
    private Collection $rows;
    private int $lastDataRow;
    private int $totalRow;

    public function __construct(Collection $rows)
    {
        $this->lastDataRow = $rows->count() + 1;
        $this->totalRow = $this->lastDataRow + 1;
        $this->rows = $rows->push((object) ['is_total' => true]);
    }

    public function collection(): Collection
    {
        return $this->rows;
    }

    public function headings(): array
    {
        return [
            'ID/Kode Tenant',
            'Nama Tenant',
            'Kategori',
            'Total Struk Terverifikasi',
            'Total Omset Belanja',
            'Total Poin Dikeluarkan',
            'Status Tenant',
        ];
    }

    public function map($row): array
    {
        if (($row->is_total ?? false) === true) {
            return [
                'TOTAL KESELURUHAN',
                '',
                '',
                "=SUM(D2:D{$this->lastDataRow})",
                "=SUM(E2:E{$this->lastDataRow})",
                "=SUM(F2:F{$this->lastDataRow})",
                '',
            ];
        }

        return [
            'TNT-' . str_pad((string) $row->id_tenant, 4, '0', STR_PAD_LEFT),
            $row->nama_tenant,
            $row->nama_kategori,
            (int) $row->total_struk,
            (float) $row->total_omzet,
            (int) $row->total_poin,
            $row->is_active ? 'Aktif' : 'Non-aktif',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'D' => NumberFormat::FORMAT_NUMBER,
            'E' => '"Rp" #,##0',
            'F' => NumberFormat::FORMAT_NUMBER,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '0F172A']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastColumn = 'G';
                $sheet->freezePane('A2');
                $sheet->setAutoFilter("A1:{$lastColumn}{$this->lastDataRow}");
                $sheet->getStyle("A1:{$lastColumn}{$this->totalRow}")
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN)
                    ->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('CBD5E1'));
                $sheet->getStyle("A1:{$lastColumn}{$this->totalRow}")->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

                for ($row = 2; $row <= $this->lastDataRow; $row++) {
                    if ($row % 2 === 0) {
                        $sheet->getStyle("A{$row}:{$lastColumn}{$row}")
                            ->getFill()
                            ->setFillType(Fill::FILL_SOLID)
                            ->getStartColor()
                            ->setRGB('F8FAFC');
                    }
                }

                $sheet->getStyle("A{$this->totalRow}:{$lastColumn}{$this->totalRow}")->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FEF3C7']],
                ]);
            },
        ];
    }

    public function properties(): array
    {
        return [
            'creator' => 'Puri Indah Mall Loyalty System',
            'company' => 'Puri Indah Mall',
            'title' => 'Laporan Performa Tenant',
            'description' => 'Executive tenant performance report',
        ];
    }
}
