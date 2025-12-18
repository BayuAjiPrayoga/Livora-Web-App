<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;

class ReportsExport implements FromCollection, WithHeadings, WithStyles, WithTitle
{
    protected $data;
    protected $stats;

    public function __construct($data, $stats)
    {
        $this->data = $data;
        $this->stats = $stats;
    }

    /**
     * Return collection of data
     */
    public function collection()
    {
        // Summary statistics rows
        $summaryRows = collect([
            ['LAPORAN PENDAPATAN LIVORA'],
            [''],
            ['Statistik', 'Nilai'],
            ['Pendapatan Bulan Ini', 'Rp ' . number_format($this->stats['current_revenue'], 0, ',', '.')],
            ['Booking Bulan Ini', $this->stats['current_bookings']],
            ['Tingkat Okupansi', $this->stats['occupancy_rate'] . '%'],
            ['Total Properti', count($this->data)],
            ['Total Kamar', $this->stats['total_rooms']],
            ['Kamar Terisi', $this->stats['occupied_rooms']],
            [''],
            ['PROPERTI TERBAIK'],
        ]);

        // Property data rows
        $propertyRows = $this->data->map(function ($property, $index) {
            return [
                $index + 1,
                $property->name,
                $property->address ?? '-',
                $property->total_rooms ?? 0,
                $property->bookings_count ?? 0,
                'Rp ' . number_format($property->total_revenue ?? 0, 0, ',', '.')
            ];
        });

        return $summaryRows->concat($propertyRows);
    }

    /**
     * Return array of headings
     */
    public function headings(): array
    {
        return [];
    }

    /**
     * Style the worksheet
     */
    public function styles(Worksheet $sheet)
    {
        // Make row 1 (title) bold and larger
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->mergeCells('A1:F1');
        
        // Style summary section header
        $sheet->getStyle('A3:B3')->getFont()->setBold(true);
        $sheet->getStyle('A3:B3')->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FF6900');
        $sheet->getStyle('A3:B3')->getFont()->getColor()->setARGB(Color::COLOR_WHITE);
        
        // Style property section header
        $rowNum = 11 + count($this->data); // After summary and before property header
        $sheet->getStyle('A11')->getFont()->setBold(true)->setSize(14);
        $sheet->mergeCells('A11:F11');
        
        // Property table headers
        $headerRow = 12;
        $sheet->getStyle("A{$headerRow}:F{$headerRow}")->getFont()->setBold(true);
        $sheet->getStyle("A{$headerRow}:F{$headerRow}")->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FF6900');
        $sheet->getStyle("A{$headerRow}:F{$headerRow}")->getFont()->getColor()->setARGB(Color::COLOR_WHITE);
        
        // Add property headers
        $sheet->setCellValue("A{$headerRow}", 'No');
        $sheet->setCellValue("B{$headerRow}", 'Nama Properti');
        $sheet->setCellValue("C{$headerRow}", 'Alamat');
        $sheet->setCellValue("D{$headerRow}", 'Total Kamar');
        $sheet->setCellValue("E{$headerRow}", 'Total Booking');
        $sheet->setCellValue("F{$headerRow}", 'Total Revenue');
        
        // Auto size columns
        foreach(range('A','F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        return [];
    }

    /**
     * Return worksheet title
     */
    public function title(): string
    {
        return 'Laporan LIVORA';
    }
}
