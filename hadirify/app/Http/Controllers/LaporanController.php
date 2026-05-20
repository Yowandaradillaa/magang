<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LaporanController extends Controller
{
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
     * Ini cara backend paling murni tanpa library tambahan.
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