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
    public function manual() 
{
    $jadwals = \App\Models\Jadwal::with(['kelas', 'mapel'])->where('id_guru', auth()->id())->get();
    
    // Default: siswa kosong sebelum jadwal dipilih
    $siswa = collect([]); 
    
    return view('guru.manual', compact('jadwals', 'siswa'));
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

    public function indexManual()
{
    // Ambil semua jadwal milik guru yang sedang login
    $jadwals = \App\Models\Jadwal::with(['mapel', 'kelas'])
                ->where('id_guru', Auth::id())
                ->get();

    // Kirim variabel $jadwals ke view
    // Kita kirim $siswa sebagai koleksi kosong agar tidak error saat pertama kali buka
    $siswa = collect([]); 
    
    return view('guru.manual', compact('jadwals', 'siswa'));
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

    /**
     * Tampilan Rekap Presensi Guru secara dinamis.
     */
    public function rekap(Request $request)
    {
        $kelas = \App\Models\Kelas::all();
        $kelasId = $request->input('kelas_id');
        $bulanInput = $request->input('bulan');

        $rekaps = [];

        if ($kelasId) {
            $siswas = User::where('id_kelas', $kelasId)->where('role', 'siswa')->orderBy('name', 'asc')->get();
            $bulan = $bulanInput ? intval($bulanInput) : now()->month;
            $tahun = now()->year;

            foreach ($siswas as $siswa) {
                $hadir = Absensi::where('siswa_id', $siswa->id)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('status', 'H')->count();
                $sakit = Absensi::where('siswa_id', $siswa->id)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('status', 'S')->count();
                $izin = Absensi::where('siswa_id', $siswa->id)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('status', 'I')->count();
                $alpa = Absensi::where('siswa_id', $siswa->id)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('status', 'A')->count();

                $rekapObj = new \stdClass();
                $rekapObj->siswa = $siswa;
                $rekapObj->hadir = $hadir;
                $rekapObj->sakit = $sakit;
                $rekapObj->izin = $izin;
                $rekapObj->alpa = $alpa;

                $rekaps[] = $rekapObj;
            }
        }

        return view('guru.rekap', compact('kelas', 'rekaps', 'kelasId', 'bulanInput'));
    }

    /**
     * Tampilan Halaman Pengumuman Guru.
     */
    public function showPengumuman()
    {
        $kelas = \App\Models\Kelas::all();
        $pengumumans = Pengumuman::with('kelas')
            ->where('guru_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('guru.pengumuman', compact('kelas', 'pengumumans'));
    }
}