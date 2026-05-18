<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    // Lihat rekap kehadiran diri sendiri (Flowchart: Dashboard Siswa)
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

        return response()->json([
            'statistik' => [
                'hadir' => $hadir,
                'izin'  => $izin,
                'sakit' => $sakit,
                'alpa'  => $alpa,
            ],
            'history' => $history
        ]);
    }
}