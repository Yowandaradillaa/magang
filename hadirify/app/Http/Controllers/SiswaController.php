<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Pengumuman;
use App\Models\PengajuanIzin;
use App\Models\Jadwal; // Pastikan Model Jadwal di-import
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    /**
     * 1. Menampilkan Dashboard Siswa
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

        $riwayatIzin = PengajuanIzin::where('siswa_id', $siswaId)->orderBy('created_at', 'desc')->take(5)->get();
        $pengumuman = Pengumuman::with('guru')->where('kelas_id', $user->id_kelas)->orderBy('created_at', 'desc')->take(5)->get();

        $dataAbsenBulanIni = Absensi::where('siswa_id', $siswaId)
                                    ->whereMonth('tanggal', now()->month)
                                    ->whereYear('tanggal', now()->year)
                                    ->get()
                                    ->pluck('status', 'tanggal');

        return view('siswa.dashboard', compact('statistik', 'pengumuman', 'riwayatIzin', 'dataAbsenBulanIni'));
    }

    /**
     * BARU: Menampilkan Halaman Scan QR dengan Jadwal Dinamis
     */
    public function scanQR()
    {
        $user = auth()->user();
        
        // Ambil nama hari ini dalam Bahasa Indonesia (Misal: Senin)
        // Jika di database kamu kolom 'hari' isinya bahasa Inggris, ganti format('l')
        $hariIni = now()->translatedFormat('l'); 

        // Ambil Jadwal khusus hari ini untuk kelas siswa tersebut
        $jadwalHariIni = Jadwal::with(['mapel', 'guru', 'absensis' => function($q) {
            // Kita juga ambil data absen siswa tersebut hari ini untuk memunculkan badge "Hadir"
            $q->where('siswa_id', auth()->id())
              ->where('tanggal', now()->toDateString());
        }])
        ->where('id_kelas', $user->id_kelas)
        ->where('hari', $hariIni)
        ->orderBy('jam_mulai', 'asc')
        ->get();

        return view('siswa.scan-qr', compact('jadwalHariIni'));
    }

    /**
     * 3. Menampilkan Halaman Tabel Rekap Kehadiran
     */
    public function rekap(Request $request)
    {
        $siswaId = auth()->id();
        $history = Absensi::with('jadwal.mapel')
                          ->where('siswa_id', $siswaId)
                          ->orderBy('tanggal', 'desc')
                          ->get();

        return view('siswa.rekap', compact('history'));
    }

    /**
     * 4. Menampilkan daftar semua notifikasi/pengumuman
     */
    public function pengumuman()
{
    $user = \App\Models\User::find(auth()->id()); // Ambil data user dari DB
    
    // Update waktu lihat terakhir ke detik ini
    $user->notification_last_viewed_at = now();
    $user->save(); // Simpan paksa ke database

    $notifikasi = Pengumuman::with('guru')
                            ->where('kelas_id', $user->id_kelas)
                            ->orderBy('created_at', 'desc')
                            ->get();

    return view('siswa.notifikasi', compact('notifikasi'));
}
}