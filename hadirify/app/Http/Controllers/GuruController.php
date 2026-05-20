<?php

namespace App\Http\Controllers; // <-- Namespace sudah disesuaikan, bukan API lagi

use App\Models\User;
use App\Models\Absensi;
use App\Models\Jadwal;
use App\Models\Pengumuman;
use Illuminate\Http\Request;

class GuruController extends Controller
{
    // 1. Ambil daftar siswa dan arahkan ke halaman Input Manual
    public function getSiswaByJadwal($jadwalId)
    {
        $jadwal = Jadwal::findOrFail($jadwalId);
        $siswa = User::where('id_kelas', $jadwal->id_kelas)
                     ->where('role', 'siswa')
                     ->get();
        
        // Membawa data jadwal dan siswa ke halaman Blade guru.manual
        return view('guru.manual', compact('jadwal', 'siswa'));
    }

    // 2. Input Absensi Manual (Hadir / Alpa / Sakit / Izin)
    public function storeManual(Request $request)
    {
        $request->validate([
            'jadwal_id' => 'required|exists:jadwals,id',
            'absensi_data' => 'required|array', 
        ]);

        foreach ($request->absensi_data as $data) {
            Absensi::updateOrCreate(
                [
                    'siswa_id' => $data['siswa_id'],
                    'jadwal_id' => $request->jadwal_id,
                    'tanggal' => now()->toDateString(),
                ],
                [
                    'status' => $data['status'],
                    'metode' => 'Manual',
                    'waktu_absen' => now(),
                ]
            );
        }

        // Kembali ke halaman sebelumnya dengan pesan sukses
        return redirect()->back()->with('success', 'Absensi manual berhasil disimpan!');
    }

    // 3. Kirim Pengumuman
    public function kirimPengumuman(Request $request)
    {
        $request->validate([
            'id_kelas' => 'required|exists:kelas,id',
            'judul'    => 'required|string',
            'isi'      => 'required|string',
        ]);

        Pengumuman::create([
            'id_guru'  => auth()->id(),
            'id_kelas' => $request->id_kelas,
            'judul'    => $request->judul,
            'isi'      => $request->isi,
            'tanggal'  => now(),
        ]);

        return redirect()->back()->with('success', 'Pengumuman berhasil dikirim!');
    }
}