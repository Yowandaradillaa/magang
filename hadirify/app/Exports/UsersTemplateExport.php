<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

/**
 * Menghasilkan file template Excel kosong (berisi contoh) untuk diisi admin.
 */
class UsersTemplateExport implements FromArray, WithHeadings, WithStyles, WithColumnWidths
{
    public function array(): array
    {
        return [
            ['Ahmad Fauzi', 'siswa', 'ahmad.fauzi@gmail.com', '0012345678', '', 'X IPA 1'],
            ['Siti Rahma', 'guru', 'siti.rahma@gmail.com', '', '1985010120100120', ''],
        ];
    }

    public function headings(): array
    {
        return ['nama', 'role', 'email', 'nisn', 'nuptk', 'kelas'];
    }

    public function columnWidths(): array
    {
        return ['A' => 25, 'B' => 12, 'C' => 30, 'D' => 15, 'E' => 22, 'F' => 15];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('D2:E' . $sheet->getHighestRow())->getNumberFormat()->setFormatCode('@');

        return [1 => ['font' => ['bold' => true]]];
    }
}