<?php

<<<<<<< HEAD
namespace App\Http\Controllers; // Namespace diubah (hapus \API)

use App\Models\Absensi;
use App\Models\Pengumuman;
=======
namespace App\Http\Controllers; // <-- Namespace sudah disesuaikan, bukan API lagi

use App\Models\Absensi;
>>>>>>> 0c8fa913973fc755b106b6b026a4b14f26e781e7
use Illuminate\Http\Request;

class SiswaController extends Controller
{
<<<<<<< HEAD
    /**
     * Menampilkan Dashboard Siswa berisi Statistik dan Riwayat Absen.
     */
    public function rekap(Request $request)
    {
        $siswaId = auth()->id();
        $user = auth()->user();
        
        // 1. Hitung Statistik untuk Dashboard
        $statistik = [
            'hadir' => Absensi::where('siswa_id', $siswaId)->where('status', 'H')->count(),
            'izin'  => Absensi::where('siswa_id', $siswaId)->where('status', 'I')->count(),
            'sakit' => Absensi::where('siswa_id', $siswaId)->where('status', 'S')->count(),
            'alpa'  => Absensi::where('siswa_id', $siswaId)->where('status', 'A')->count(),
        ];

        // 2. Ambil Riwayat Absensi lengkap dengan nama Mapel
=======
    // Lihat rekap kehadiran dan kirim ke Dashboard / Rekap Siswa
    public function rekap(Request $request)
    {
        $siswaId = auth()->id();
        
        $hadir = Absensi::where('siswa_id', $siswaId)->where('status', 'H')->count();
        $izin  = Absensi::where('siswa_id', $siswaId)->where('status', 'I')->count();
        $sakit = Absensi::where('siswa_id', $siswaId)->where('status', 'S')->count();
        $alpa  = Absensi::where('siswa_id', $siswaId)->where('status', 'A')->count();

>>>>>>> 0c8fa913973fc755b106b6b026a4b14f26e781e7
        $history = Absensi::with('jadwal.mapel')
                          ->where('siswa_id', $siswaId)
                          ->orderBy('tanggal', 'desc')
                          ->get();

<<<<<<< HEAD
        // 3. Ambil Pengumuman terbaru untuk kelas siswa ini
        $pengumuman = Pengumuman::with('guru')
                                ->where('id_kelas', $user->id_kelas)
                                ->orderBy('created_at', 'desc')
                                ->take(5) // Ambil 5 terbaru
                                ->get();

        // 4. Kirim data ke View (siswa/dashboard.blade.php)
        return view('siswa.dashboard', compact('statistik', 'history', 'pengumuman'));
=======
        // Mengirim statistik dan riwayat (history) ke halaman rekap siswa
        return view('siswa.rekap', compact('hadir', 'izin', 'sakit', 'alpa', 'history'));
>>>>>>> 0c8fa913973fc755b106b6b026a4b14f26e781e7
    }
}