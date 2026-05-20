<?php

namespace App\Http\Controllers; // <-- Namespace sudah disesuaikan, bukan API lagi

use App\Models\Absensi;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    // Lihat rekap kehadiran dan kirim ke Dashboard / Rekap Siswa
    public function rekap(Request $request)
    {
        $siswaId = auth()->id();
        
        $hadir = Absensi::where('siswa_id', $siswaId)->where('status', 'H')->count();
        $izin  = Absensi::where('siswa_id', $siswaId)->where('status', 'I')->count();
        $sakit = Absensi::where('siswa_id', $siswaId)->where('status', 'S')->count();
        $alpa  = Absensi::where('siswa_id', $siswaId)->where('status', 'A')->count();

        $history = Absensi::with('jadwal.mapel')
                          ->where('siswa_id', $siswaId)
                          ->orderBy('tanggal', 'desc')
                          ->get();

        // Mengirim statistik dan riwayat (history) ke halaman rekap siswa
        return view('siswa.rekap', compact('hadir', 'izin', 'sakit', 'alpa', 'history'));
    }
}