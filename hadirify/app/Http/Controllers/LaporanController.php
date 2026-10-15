<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Kelas;
use App\Models\User;
use App\Exports\LaporanKehadiranExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LaporanController extends Controller
{
    /**
     * Export Laporan Kehadiran ke PDF menggunakan barryvdh/laravel-dompdf
     */
    public function exportPdf(Request $request)
    {
        $periodeInfo = $this->parsePeriode($request);
        $bulan = $periodeInfo['bulan'];
        $tahun = $periodeInfo['tahun'];
        $periode = $periodeInfo['periode'];
        $kelasId = $request->input('kelas_id');

        $namaKelasDipilih = 'Semua Kelas';
        if (!empty($kelasId) && $kelasId !== 'Semua Kelas' && $kelasId !== 'null') {
            $kelasObj = Kelas::find($kelasId);
            if ($kelasObj) {
                $namaKelasDipilih = $kelasObj->nama_kelas;
            }
        }

        $data = $this->getRekapData($bulan, $tahun, $kelasId);
        $laporans = $data['laporans'];
        $totals = $data['totals'];

        $pdf = Pdf::loadView('admin.laporan.pdf', compact('laporans', 'totals', 'periode', 'bulan', 'tahun', 'namaKelasDipilih'));
        $pdf->setPaper('a4', 'portrait');

        $sanitizedKelas = str_replace([' ', '/', '\\'], '-', $namaKelasDipilih);
        $filename = "Laporan_Kehadiran_SMA_Muh_7_{$sanitizedKelas}_{$bulan}_{$tahun}.pdf";

        return $pdf->download($filename);
    }

    /**
     * Export Laporan Kehadiran ke Excel menggunakan Maatwebsite/Laravel-Excel
     */
    public function exportExcel(Request $request)
    {
        $periodeInfo = $this->parsePeriode($request);
        $bulan = $periodeInfo['bulan'];
        $tahun = $periodeInfo['tahun'];
        $periode = $periodeInfo['periode'];
        $kelasId = $request->input('kelas_id');

        $namaKelasDipilih = 'Semua Kelas';
        if (!empty($kelasId) && $kelasId !== 'Semua Kelas' && $kelasId !== 'null') {
            $kelasObj = Kelas::find($kelasId);
            if ($kelasObj) {
                $namaKelasDipilih = $kelasObj->nama_kelas;
            }
        }

        $data = $this->getRekapData($bulan, $tahun, $kelasId);
        $laporans = $data['laporans'];
        $totals = $data['totals'];

        $sanitizedKelas = str_replace([' ', '/', '\\'], '-', $namaKelasDipilih);
        $filename = "Laporan_Kehadiran_SMA_Muh_7_{$sanitizedKelas}_{$bulan}_{$tahun}.xlsx";

        return Excel::download(new LaporanKehadiranExport($laporans, $periode, $totals), $filename);
    }

    /**
     * Helper: Parse periode bulan & tahun dari request
     * Mendukung format:
     * - "10" / 10 (angka bulan dari select option)
     * - "2026-10" (format YYYY-MM)
     * - "Oktober 2026" (nama bulan Indonesia + tahun)
     * - "Oktober" (hanya nama bulan)
     */
    public function parsePeriode(Request $request): array
    {
        $input = $request->input('periode_bulan') ?? $request->input('bulan');
        $tahun = (int) ($request->input('tahun') ?? now()->year);
        $bulan = (int) now()->month;

        $daftarBulan = [
            'januari' => 1, 'februari' => 2, 'maret' => 3, 'april' => 4,
            'mei' => 5, 'juni' => 6, 'juli' => 7, 'agustus' => 8,
            'september' => 9, 'oktober' => 10, 'november' => 11, 'desember' => 12,
        ];

        if ($input) {
            $inputStr = trim(strtolower((string)$input));

            // Format YYYY-MM, contoh: 2026-10
            if (preg_match('/^(\d{4})-(\d{1,2})$/', $inputStr, $matches)) {
                $tahun = (int) $matches[1];
                $bulan = (int) $matches[2];
            }
            // Format: "Oktober 2026"
            elseif (preg_match('/^([a-z]+)\s+(\d{4})$/', $inputStr, $matches)) {
                if (isset($daftarBulan[$matches[1]])) {
                    $bulan = $daftarBulan[$matches[1]];
                }
                $tahun = (int) $matches[2];
            }
            // Format nama bulan saja: "Oktober"
            elseif (isset($daftarBulan[$inputStr])) {
                $bulan = $daftarBulan[$inputStr];
            }
            // Format angka 1-12
            elseif (is_numeric($inputStr) && (int)$inputStr >= 1 && (int)$inputStr <= 12) {
                $bulan = (int) $inputStr;
            }
        }

        $namaBulanIndonesia = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $namaBulan = $namaBulanIndonesia[$bulan] ?? 'Januari';
        $periodeFormatted = "{$namaBulan} {$tahun}";

        return [
            'bulan' => $bulan,
            'tahun' => $tahun,
            'nama_bulan' => $namaBulan,
            'periode' => $periodeFormatted,
        ];
    }

    /**
     * Helper: Ambil data agregat rekapitulasi kehadiran per kelas
     */
    public function getRekapData(int $bulan, int $tahun, $kelasId = null): array
    {
        $kelasQuery = Kelas::query();
        if (!empty($kelasId) && $kelasId !== 'Semua Kelas' && $kelasId !== 'null') {
            $kelasQuery->where('id', $kelasId);
        }
        $kelasList = $kelasQuery->orderBy('nama_kelas', 'asc')->get();

        $laporans = [];
        $totalHadir = 0;
        $totalSakit = 0;
        $totalIzin = 0;
        $totalAlpa = 0;

        foreach ($kelasList as $k) {
            $hadir = Absensi::whereHas('user', function ($q) use ($k) {
                $q->where('id_kelas', $k->id);
            })->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('status', 'H')->count();

            $sakit = Absensi::whereHas('user', function ($q) use ($k) {
                $q->where('id_kelas', $k->id);
            })->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('status', 'S')->count();

            $izin = Absensi::whereHas('user', function ($q) use ($k) {
                $q->where('id_kelas', $k->id);
            })->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('status', 'I')->count();

            $alpa = Absensi::whereHas('user', function ($q) use ($k) {
                $q->where('id_kelas', $k->id);
            })->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('status', 'A')->count();

            $totalHari = $hadir + $sakit + $izin + $alpa;
            $persentase = $totalHari > 0 ? round(($hadir / $totalHari) * 100) : 0;

            $laporans[] = [
                'id' => $k->id,
                'nama_kelas' => $k->nama_kelas,
                'hadir' => $hadir,
                'sakit' => $sakit,
                'izin' => $izin,
                'alpa' => $alpa,
                'total' => $totalHari,
                'persentase' => $persentase,
            ];

            $totalHadir += $hadir;
            $totalSakit += $sakit;
            $totalIzin += $izin;
            $totalAlpa += $alpa;
        }

        $grandTotal = $totalHadir + $totalSakit + $totalIzin + $totalAlpa;
        $rataKehadiran = $grandTotal > 0 ? round(($totalHadir / $grandTotal) * 100) : 0;

        return [
            'laporans' => $laporans,
            'totals' => [
                'total_hadir' => $totalHadir,
                'total_sakit' => $totalSakit,
                'total_izin' => $totalIzin,
                'total_alpa' => $totalAlpa,
                'total_semua' => $grandTotal,
                'rata_kehadiran' => $rataKehadiran,
            ]
        ];
    }

    /**
     * Menampilkan data rekap untuk dpreview di web sebelum di-export.
     */
    public function index(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'bulan'    => 'required|integer|between:1,12',
            'tahun'    => 'required|integer',
        ]);

        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $kelasId = $request->kelas_id;

        // 1. Ambil data kelas
        $kelas = Kelas::findOrFail($kelasId);

        // 2. Ambil semua siswa di kelas tersebut
        $siswas = User::where('id_kelas', $kelasId)
                      ->where('role', 'siswa')
                      ->orderBy('name', 'asc')
                      ->get();

        // 3. Susun data rekap per siswa
        $rekap = [];
        foreach ($siswas as $siswa) {
            $rekap[] = [
                'name'  => $siswa->name,
                'nisn'  => $siswa->nisn,
                'hadir' => Absensi::where('siswa_id', $siswa->id)->whereYear('tanggal', $tahun)->whereMonth('tanggal', $bulan)->where('status', 'H')->count(),
                'izin'  => Absensi::where('siswa_id', $siswa->id)->whereYear('tanggal', $tahun)->whereMonth('tanggal', $bulan)->where('status', 'I')->count(),
                'sakit' => Absensi::where('siswa_id', $siswa->id)->whereYear('tanggal', $tahun)->whereMonth('tanggal', $bulan)->where('status', 'S')->count(),
                'alpa'  => Absensi::where('siswa_id', $siswa->id)->whereYear('tanggal', $tahun)->whereMonth('tanggal', $bulan)->where('status', 'A')->count(),
            ];
        }

        return view('admin.laporan-view', compact('rekap', 'kelas', 'bulan', 'tahun'));
    }

    /**
     * Export ke format CSV (Bisa dibuka di Excel).
     */
    public function exportCSV(Request $request)
    {
        $id_kelas = $request->kelas_id;
        $bulan = $request->bulan;
        $tahun = $request->tahun;

        $kelas = Kelas::find($id_kelas);
        $siswas = User::where('id_kelas', $id_kelas)->where('role', 'siswa')->get();

        $fileName = "Rekap_Absensi_{$kelas->nama_kelas}_{$bulan}_{$tahun}.csv";

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array('Nama Siswa', 'NISN', 'Hadir', 'Izin', 'Sakit', 'Alpa');

        $callback = function() use($siswas, $columns, $bulan, $tahun) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($siswas as $siswa) {
                $h = Absensi::where('siswa_id', $siswa->id)->whereYear('tanggal', $tahun)->whereMonth('tanggal', $bulan)->where('status', 'H')->count();
                $i = Absensi::where('siswa_id', $siswa->id)->whereYear('tanggal', $tahun)->whereMonth('tanggal', $bulan)->where('status', 'I')->count();
                $s = Absensi::where('siswa_id', $siswa->id)->whereYear('tanggal', $tahun)->whereMonth('tanggal', $bulan)->where('status', 'S')->count();
                $a = Absensi::where('siswa_id', $siswa->id)->whereYear('tanggal', $tahun)->whereMonth('tanggal', $bulan)->where('status', 'A')->count();

                fputcsv($file, array($siswa->name, $siswa->nisn, $h, $i, $s, $a));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}