<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

/**
 * Class Export Excel untuk Rekapitulasi Laporan Kehadiran Siswa SMA Muhammadiyah 7
 */
class LaporanKehadiranExport implements FromArray, WithStyles, WithColumnWidths
{
    protected array $laporans;
    protected string $periode;
    protected array $totals;

    public function __construct(array $laporans, string $periode, array $totals = [])
    {
        $this->laporans = $laporans;
        $this->periode = $periode;
        $this->totals = $totals;
    }

    /**
     * Menyusun data baris Excel: Judul, Periode, Heading Tabel, Data, dan Total
     */
    public function array(): array
    {
        $rows = [
            ['LAPORAN KEHADIRAN SISWA SMA MUHAMMADIYAH 7'],
            ['Periode: ' . $this->periode],
            [''], // Baris kosong pemisah
            [
                'Nama Kelas',
                'Total Hadir',
                'Total Sakit',
                'Total Izin',
                'Total Alpa',
                'Persentase Kehadiran (%)'
            ],
        ];

        $totHadir = 0;
        $totSakit = 0;
        $totIzin = 0;
        $totAlpa = 0;

        foreach ($this->laporans as $item) {
            $totHadir += $item['hadir'];
            $totSakit += $item['sakit'];
            $totIzin += $item['izin'];
            $totAlpa += $item['alpa'];

            $rows[] = [
                $item['nama_kelas'],
                $item['hadir'],
                $item['sakit'],
                $item['izin'],
                $item['alpa'],
                $item['persentase'] . '%',
            ];
        }

        // Baris Total / Agregat Keseluruhan
        $grandTotalHadir = $this->totals['total_hadir'] ?? $totHadir;
        $grandTotalSakit = $this->totals['total_sakit'] ?? $totSakit;
        $grandTotalIzin  = $this->totals['total_izin']  ?? $totIzin;
        $grandTotalAlpa  = $this->totals['total_alpa']  ?? $totAlpa;
        
        $totalSemua = $grandTotalHadir + $grandTotalSakit + $grandTotalIzin + $grandTotalAlpa;
        $rataPersen = $totalSemua > 0 ? round(($grandTotalHadir / $totalSemua) * 100) : 0;

        $rows[] = [
            'TOTAL KESELURUHAN',
            $grandTotalHadir,
            $grandTotalSakit,
            $grandTotalIzin,
            $grandTotalAlpa,
            $rataPersen . '%',
        ];

        return $rows;
    }

    /**
     * Lebar masing-masing kolom
     */
    public function columnWidths(): array
    {
        return [
            'A' => 28,
            'B' => 16,
            'C' => 16,
            'D' => 16,
            'E' => 16,
            'F' => 28,
        ];
    }

    /**
     * Styling tampilan Excel
     */
    public function styles(Worksheet $sheet)
    {
        $highestRow = $sheet->getHighestRow();

        // 1. Styling Judul Laporan (Row 1 & 2)
        $sheet->mergeCells('A1:F1');
        $sheet->mergeCells('A2:F2');

        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 14,
                'color' => ['rgb' => '0F172A'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        $sheet->getStyle('A2')->applyFromArray([
            'font' => [
                'italic' => true,
                'size' => 11,
                'color' => ['rgb' => '475569'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        $sheet->getRowDimension(1)->setRowHeight(25);
        $sheet->getRowDimension(2)->setRowHeight(20);
        $sheet->getRowDimension(4)->setRowHeight(26);

        // 2. Styling Header Tabel (Row 4)
        $sheet->getStyle('A4:F4')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 10,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '0F172A'], // Slate-900
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
        ]);

        // 3. Styling Data Rows & Total Row
        if ($highestRow >= 5) {
            // Rata kiri untuk Nama Kelas, Rata tengah untuk angka
            $sheet->getStyle('A5:A' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $sheet->getStyle('B5:F' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            // Styling Baris Total Keseluruhan (baris terakhir)
            $sheet->getStyle('A' . $highestRow . ':F' . $highestRow)->applyFromArray([
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => '0F172A'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E2E8F0'], // Slate-200
                ],
            ]);

            $sheet->getRowDimension($highestRow)->setRowHeight(24);

            // Border untuk seluruh tabel dari A4 sampai F{highestRow}
            $sheet->getStyle('A4:F' . $highestRow)->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'CBD5E1'],
                    ],
                ],
            ]);
        }

        return [];
    }
}
