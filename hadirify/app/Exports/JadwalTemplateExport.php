<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class JadwalTemplateExport implements FromArray, WithHeadings, WithStyles, WithColumnWidths
{
    public function array(): array
    {
        return [
            ['Senin', '07:30 - 09:00', 'X-1', 'Matematika Wajib', 'Siti Rahma'],
            ['Senin', '09:15 - 10:45', 'X-1', 'Bahasa Indonesia', 'Ahmad Fauzi'],
            ['Selasa', '07:30 - 09:00', 'X-2', 'Matematika Wajib', 'Siti Rahma'],
        ];
    }

    public function headings(): array
    {
        return ['Hari', 'Waktu', 'Nama Kelas', 'Nama Mapel', 'Nama Guru'];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 15,
            'B' => 20,
            'C' => 20,
            'D' => 28,
            'E' => 30,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '0B1E36'],
                ],
            ],
        ];
    }
}
