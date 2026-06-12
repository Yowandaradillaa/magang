<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Pengumuman;
use App\Models\PengajuanIzin;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    /**
     * 1. Menampilkan Dashboard Siswa (Berisi Statistik, Riwayat Izin, Pengumuman)
     */
    public function dashboard(Request $request)
    {
        $siswaId = auth()->id();
        $user = auth()->user();
        
        $statistik = [
            'hadir' => Absensi::where('siswa_id', $siswaId)->where('status', 'H')->count(),
            'izin'  => Absensi::where('siswa_id', $siswaId)->where('status', 'I')->count(),
            'sakit' => Absensi::where('siswa_id', $siswaId)->where('status', 'S')->count(),
            'alpa'  => Absensi::where('siswa_id', $siswaId)->where('status', 'A')->count(),
        ];

        // Ambil riwayat izin & pengumuman untuk dashboard
        $riwayatIzin = PengajuanIzin::where('siswa_id', $siswaId)->orderBy('created_at', 'desc')->take(5)->get();
        $pengumuman = Pengumuman::with('guru')->where('kelas_id', $user->id_kelas)->orderBy('created_at', 'desc')->take(5)->get();

        // TAMBAHAN: Data untuk Kalender Dinamis (Jika ada)
        $dataAbsenBulanIni = Absensi::where('siswa_id', $siswaId)
                                    ->whereMonth('tanggal', now()->month)
                                    ->whereYear('tanggal', now()->year)
                                    ->get()
                                    ->pluck('status', 'tanggal');

        // BUKA FILE DASHBOARD
        return view('siswa.dashboard', compact('statistik', 'pengumuman', 'riwayatIzin', 'dataAbsenBulanIni'));
    }

    /**
     * 2. Menampilkan Halaman Tabel Rekap Kehadiran
     */
    public function rekap(Request $request)
    {
        $siswaId = auth()->id();
        
        // Ambil SEMUA Riwayat Absensi untuk ditampilkan di tabel rekap
        $history = Absensi::with('jadwal.mapel')
                          ->where('siswa_id', $siswaId)
                          ->orderBy('tanggal', 'desc')
                          ->get();

        // BUKA FILE REKAP
        return view('siswa.rekap', compact('history'));
    }

    /**
     * 3. Menampilkan daftar semua notifikasi/pengumuman untuk siswa
     */
    public function pengumuman()
    {
        $user = auth()->user();

        $notifikasi = Pengumuman::with('guru')
                                ->where('kelas_id', $user->id_kelas)
                                ->orderBy('created_at', 'desc')
                                ->get();

        return view('siswa.notifikasi', compact('notifikasi'));
    }
}