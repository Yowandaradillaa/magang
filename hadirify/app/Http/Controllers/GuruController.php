<?php

namespace App\Http\Controllers; 

use App\Models\User;
use App\Models\Absensi;
use App\Models\Jadwal;
use App\Models\Pengumuman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuruController extends Controller
{
    /**
     * Dashboard Guru: Menampilkan statistik singkat kehadiran hari ini (Global)
     */
    public function dashboardStats()
    {
        $today = now()->toDateString();
        
        $stats = [
            'hadir' => Absensi::where('tanggal', $today)->where('status', 'H')->count(),
            'izin'  => Absensi::where('tanggal', $today)->where('status', 'I')->count(),
            'sakit' => Absensi::where('tanggal', $today)->where('status', 'S')->count(),
            'alpa'  => Absensi::where('tanggal', $today)->where('status', 'A')->count(),
        ];

        return view('guru.dashboard', compact('stats'));
    }

    /**
     * BARU: Menampilkan daftar jadwal mengajar guru hari ini.
     * Digunakan agar guru bisa memilih rute ke "Generate QR" atau "Manual"
     */
    public function indexJadwal()
    {
        $hariIni = now()->locale('id')->dayName; // Mengambil nama hari (Senin, Selasa, dst)
        $jadwals = Jadwal::with(['mapel', 'kelas'])
                         ->where('id_guru', Auth::id())
                         ->where('hari', $hariIni)
                         ->get();

        return view('guru.jadwal-hari-ini', compact('jadwals'));
    }

    /**
     * Menampilkan halaman daftar siswa untuk absen manual berdasarkan jadwal.
     */
    public function getSiswaByJadwal($jadwalId)
    {
        $jadwal = Jadwal::with(['mapel', 'kelas'])->findOrFail($jadwalId);
        
        // Ambil siswa berdasarkan 'id_kelas' yang ada di jadwal tersebut
        $siswa = User::where('id_kelas', $jadwal->id_kelas)
                     ->where('role', 'siswa')
                     ->get();
        
        return view('guru.manual', compact('siswa', 'jadwal'));
    }

    /**
     * Simpan Absensi Manual (Dari Form Blade)
     */
    public function storeManual(Request $request)
    {
        $request->validate([
            'jadwal_id' => 'required|exists:jadwals,id',
            'absensi_data' => 'required|array', 
        ]);

        foreach ($request->absensi_data as $siswaId => $status) {
            Absensi::updateOrCreate(
                [
                    'siswa_id' => $siswaId,
                    'jadwal_id' => $request->jadwal_id,
                    'tanggal' => now()->toDateString(),
                ],
                [
                    'status' => $status, // H, A, S, I
                    'metode' => 'Manual',
                    'waktu_absen' => now(),
                ]
            );
        }

        return redirect()->route('guru.dashboard')->with('success', 'Absensi manual berhasil disimpan!');
    }

    /**
     * BARU: Fitur Tutup Absensi (Logika Auto-Alpa)
     * Siswa yang belum absen (kosong) otomatis dibuatkan record status 'A' (Alpa)
     */
    public function tutupAbsensi($jadwalId)
    {
        $jadwal = Jadwal::findOrFail($jadwalId);
        $today = now()->toDateString();

        // 1. Ambil semua siswa yang ada di kelas jadwal tersebut
        $semuaSiswa = User::where('id_kelas', $jadwal->id_kelas)
                          ->where('role', 'siswa')
                          ->get();

        $countAlpa = 0;

        foreach ($semuaSiswa as $siswa) {
            // 2. Cek apakah siswa ini sudah punya data absen (Hadir/Izin/Sakit)
            $exists = Absensi::where('siswa_id', $siswa->id)
                             ->where('jadwal_id', $jadwalId)
                             ->where('tanggal', $today)
                             ->exists();

            // 3. Jika belum ada data sama sekali, masukkan sebagai Alpa
            if (!$exists) {
                Absensi::create([
                    'siswa_id'    => $siswa->id,
                    'jadwal_id'   => $jadwalId,
                    'tanggal'     => $today,
                    'waktu_absen' => now(),
                    'status'      => 'A', // Alpa
                    'metode'      => 'Manual',
                ]);
                $countAlpa++;
            }
        }

        return redirect()->route('guru.dashboard')->with('success', "Absensi ditutup. $countAlpa siswa otomatis ditandai Alpa.");
    }

    /**
     * Simpan Pengumuman (Dari Form Blade)
     */
    public function kirimPengumuman(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'judul'    => 'required|string|max:200',
            'isi'      => 'required|string',
        ]);

        Pengumuman::create([
            'guru_id'  => Auth::id(),
            'kelas_id' => $request->kelas_id,
            'judul'    => $request->judul,
            'isi'      => $request->isi,
            'tanggal'  => now(),
        ]);

        return redirect()->back()->with('success', 'Pengumuman berhasil dikirim ke kelas!');
    }
}