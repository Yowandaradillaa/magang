<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MapelTemplateExport implements FromArray, WithHeadings, WithStyles, WithColumnWidths
{
    public function array(): array
    {
        return [
            ['Matematika Wajib', 'MTK-X', 'Matematika Wajib untuk Tingkat X'],
            ['Bahasa Indonesia', 'BINDO-X', 'Bahasa Indonesia Tingkat X'],
            ['Pendidikan Agama Islam', 'PAI-X', 'PAI dan Budi Pekerti'],
        ];
    }

    public function headings(): array
    {
        return ['Nama Mapel', 'Kode Mapel', 'Deskripsi'];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 28,
            'B' => 18,
            'C' => 35,
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
