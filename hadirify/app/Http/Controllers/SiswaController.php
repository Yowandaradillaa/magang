<?php

namespace App\Http\Controllers; // Namespace diubah (hapus \API)

use App\Models\Absensi;
use App\Models\Pengumuman;
use App\Models\PengajuanIzin; // Tambahkan import model ini
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    /**
     * Menampilkan Dashboard Siswa berisi Statistik, Riwayat Absen, dan Riwayat Izin.
     */
    public function rekap(Request $request)
    {
        $siswaId = auth()->id();
        $user = auth()->user();
        
        // 1. Hitung Statistik untuk Dashboard (Hadir, Izin, Sakit, Alpa)
        $statistik = [
            'hadir' => Absensi::where('siswa_id', $siswaId)->where('status', 'H')->count(),
            'izin'  => Absensi::where('siswa_id', $siswaId)->where('status', 'I')->count(),
            'sakit' => Absensi::where('siswa_id', $siswaId)->where('status', 'S')->count(),
            'alpa'  => Absensi::where('siswa_id', $siswaId)->where('status', 'A')->count(),
        ];

        // 2. Ambil Riwayat Absensi (Record yang sudah masuk ke tabel absensis)
        $history = Absensi::with('jadwal.mapel')
                          ->where('siswa_id', $siswaId)
                          ->orderBy('tanggal', 'desc')
                          ->get();

        // 3. Ambil Pengumuman terbaru untuk kelas siswa ini
        $pengumuman = Pengumuman::with('guru')
                                ->where('kelas_id', $user->id_kelas)
                                ->orderBy('created_at', 'desc')
                                ->take(5)
                                ->get();

        // 4. Ambil Riwayat Pengajuan Izin
        $riwayatIzin = PengajuanIzin::where('siswa_id', $siswaId)
                                    ->orderBy('created_at', 'desc')
                                    ->take(5)
                                    ->get();

        // 5. TAMBAHAN: Data untuk Kalender Dinamis
        $dataAbsenBulanIni = Absensi::where('siswa_id', $siswaId)
                                    ->whereMonth('tanggal', now()->month)
                                    ->whereYear('tanggal', now()->year)
                                    ->get()
                                    ->pluck('status', 'tanggal');

        // 6. Kirim semua data ke View
        return view('siswa.dashboard', compact('statistik', 'history', 'pengumuman', 'riwayatIzin', 'dataAbsenBulanIni'));
    }

    /**
     * Menampilkan daftar semua notifikasi/pengumuman untuk siswa.
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