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
        $hariIni = now()->locale('id')->dayName;

        // Ambil ID jadwal dari QR Code yang sedang aktif untuk kelas siswa
        $activeQrJadwalIds = \App\Models\QRCode::where('status', 'aktif')
            ->where('waktu_expired', '>', now())
            ->whereHas('jadwal', function($q) use ($user) {
                $q->where('id_kelas', $user->id_kelas);
            })
            ->pluck('jadwal_id')
            ->toArray();

        // Ambil Jadwal khusus hari ini untuk kelas siswa tersebut (serta sesi aktif)
        $jadwalHariIni = Jadwal::with(['mapel', 'guru', 'absensis' => function($q) {
            // Kita juga ambil data absen siswa tersebut hari ini untuk memunculkan badge "Hadir"
            $q->where('siswa_id', auth()->id())
              ->where('tanggal', now()->toDateString());
        }])
        ->where('id_kelas', $user->id_kelas)
        ->where(function($q) use ($hariIni, $activeQrJadwalIds) {
            $q->where('hari', $hariIni);
            if (!empty($activeQrJadwalIds)) {
                $q->orWhereIn('id', $activeQrJadwalIds);
            }
        })
        ->orderBy('jam_mulai', 'asc')
        ->get()
        ->map(function($j) use ($activeQrJadwalIds) {
            $j->is_active_qr = in_array($j->id, $activeQrJadwalIds);
            return $j;
        });

        return view('siswa.scan-qr', compact('jadwalHariIni'));
    }

    /**
     * 3. Menampilkan Halaman Tabel Rekap Kehadiran
     */
    public function rekap(Request $request)
    {
        $siswaId = auth()->id();
        $periode = $request->input('periode', 'semua');

        $query = Absensi::with('jadwal.mapel')
                          ->where('siswa_id', $siswaId);

        if ($periode === 'hari_ini') {
            $query->whereDate('tanggal', now()->toDateString());
        } elseif ($periode === 'minggu_ini') {
            $query->whereBetween('tanggal', [now()->startOfWeek()->toDateString(), now()->endOfWeek()->toDateString()]);
        } elseif ($periode === 'bulan_ini') {
            $query->whereMonth('tanggal', now()->month)->whereYear('tanggal', now()->year);
        }

        $history = $query->orderBy('tanggal', 'desc')->latest('id')->paginate(10)->withQueryString();

        return view('siswa.rekap', compact('history', 'periode'));
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