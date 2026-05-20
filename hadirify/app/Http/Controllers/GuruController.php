<?php

namespace App\Http\Controllers; // Namespace diubah (hapus \API)

use App\Models\User;
use App\Models\Absensi;
use App\Models\Jadwal;
use App\Models\Pengumuman;
use Illuminate\Http\Request;

class GuruController extends Controller
{
    // Dashboard Guru: Menampilkan statistik singkat
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

    // 1. Menampilkan halaman daftar siswa untuk absen manual
    public function getSiswaByJadwal($jadwalId)
    {
        $jadwal = Jadwal::with('mapel', 'kelas')->findOrFail($jadwalId);
        
        // Ambil siswa yang satu kelas dengan jadwal ini
        $siswa = User::where('id_kelas', $jadwal->id_kelas)
                     ->where('role', 'siswa')
                     ->get();
        
        // Return ke view input manual
        return view('guru.input-manual', compact('siswa', 'jadwal'));
    }

    // 2. Simpan Absensi Manual (Dari Form Blade)
    public function storeManual(Request $request)
    {
        $request->validate([
            'jadwal_id' => 'required|exists:jadwals,id',
            'absensi_data' => 'required|array', 
        ]);

        foreach ($request->absensi_data as $siswaId => $status) {
            // Kita asumsikan di form Blade, name inputnya: absensi_data[{{ $s->id }}]
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

        // Setelah simpan, balik ke dashboard dengan pesan sukses
        return redirect()->route('guru.dashboard')->with('success', 'Absensi manual berhasil disimpan!');
    }

    // 3. Simpan Pengumuman (Dari Form Blade)
    public function kirimPengumuman(Request $request)
    {
        $request->validate([
            'id_kelas' => 'required|exists:kelas,id',
            'judul'    => 'required|string|max:200',
            'isi'      => 'required|string',
        ]);

        Pengumuman::create([
            'id_guru'  => auth()->id(),
            'id_kelas' => $request->id_kelas,
            'judul'    => $request->judul,
            'isi'      => $request->isi,
            'tanggal'  => now(),
        ]);

        // Balik ke halaman sebelumnya dengan notifikasi
        return redirect()->back()->with('success', 'Pengumuman berhasil dikirim ke kelas!');
    }
}