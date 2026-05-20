<?php

<<<<<<< HEAD
namespace App\Http\Controllers; // Namespace diubah (hapus \API)
=======
namespace App\Http\Controllers; // <-- Namespace sudah disesuaikan, bukan API lagi
>>>>>>> 0c8fa913973fc755b106b6b026a4b14f26e781e7

use App\Models\User;
use App\Models\Absensi;
use App\Models\Jadwal;
use App\Models\Pengumuman;
use Illuminate\Http\Request;

class GuruController extends Controller
{
<<<<<<< HEAD
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
=======
    // 1. Ambil daftar siswa dan arahkan ke halaman Input Manual
    public function getSiswaByJadwal($jadwalId)
    {
        $jadwal = Jadwal::findOrFail($jadwalId);
>>>>>>> 0c8fa913973fc755b106b6b026a4b14f26e781e7
        $siswa = User::where('id_kelas', $jadwal->id_kelas)
                     ->where('role', 'siswa')
                     ->get();
        
<<<<<<< HEAD
        // Return ke view input manual
        return view('guru.input-manual', compact('siswa', 'jadwal'));
    }

    // 2. Simpan Absensi Manual (Dari Form Blade)
=======
        // Membawa data jadwal dan siswa ke halaman Blade guru.manual
        return view('guru.manual', compact('jadwal', 'siswa'));
    }

    // 2. Input Absensi Manual (Hadir / Alpa / Sakit / Izin)
>>>>>>> 0c8fa913973fc755b106b6b026a4b14f26e781e7
    public function storeManual(Request $request)
    {
        $request->validate([
            'jadwal_id' => 'required|exists:jadwals,id',
            'absensi_data' => 'required|array', 
        ]);

<<<<<<< HEAD
        foreach ($request->absensi_data as $siswaId => $status) {
            // Kita asumsikan di form Blade, name inputnya: absensi_data[{{ $s->id }}]
            Absensi::updateOrCreate(
                [
                    'siswa_id' => $siswaId,
=======
        foreach ($request->absensi_data as $data) {
            Absensi::updateOrCreate(
                [
                    'siswa_id' => $data['siswa_id'],
>>>>>>> 0c8fa913973fc755b106b6b026a4b14f26e781e7
                    'jadwal_id' => $request->jadwal_id,
                    'tanggal' => now()->toDateString(),
                ],
                [
<<<<<<< HEAD
                    'status' => $status, // H, A, S, I
=======
                    'status' => $data['status'],
>>>>>>> 0c8fa913973fc755b106b6b026a4b14f26e781e7
                    'metode' => 'Manual',
                    'waktu_absen' => now(),
                ]
            );
        }

<<<<<<< HEAD
        // Setelah simpan, balik ke dashboard dengan pesan sukses
        return redirect()->route('guru.dashboard')->with('success', 'Absensi manual berhasil disimpan!');
    }

    // 3. Simpan Pengumuman (Dari Form Blade)
=======
        // Kembali ke halaman sebelumnya dengan pesan sukses
        return redirect()->back()->with('success', 'Absensi manual berhasil disimpan!');
    }

    // 3. Kirim Pengumuman
>>>>>>> 0c8fa913973fc755b106b6b026a4b14f26e781e7
    public function kirimPengumuman(Request $request)
    {
        $request->validate([
            'id_kelas' => 'required|exists:kelas,id',
<<<<<<< HEAD
            'judul'    => 'required|string|max:200',
=======
            'judul'    => 'required|string',
>>>>>>> 0c8fa913973fc755b106b6b026a4b14f26e781e7
            'isi'      => 'required|string',
        ]);

        Pengumuman::create([
            'id_guru'  => auth()->id(),
            'id_kelas' => $request->id_kelas,
            'judul'    => $request->judul,
            'isi'      => $request->isi,
            'tanggal'  => now(),
        ]);

<<<<<<< HEAD
        // Balik ke halaman sebelumnya dengan notifikasi
        return redirect()->back()->with('success', 'Pengumuman berhasil dikirim ke kelas!');
=======
        return redirect()->back()->with('success', 'Pengumuman berhasil dikirim!');
>>>>>>> 0c8fa913973fc755b106b6b026a4b14f26e781e7
    }
}