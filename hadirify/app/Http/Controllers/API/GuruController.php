<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Absensi;
use App\Models\Jadwal;
use App\Models\Pengumuman;
use Illuminate\Http\Request;

class GuruController extends Controller
{
    // 1. Ambil daftar siswa di kelas tertentu (berdasarkan jadwal)
    public function getSiswaByJadwal($jadwalId)
    {
        $jadwal = Jadwal::findOrFail($jadwalId);
        $siswa = User::where('id_kelas', $jadwal->id_kelas)
                     ->where('role', 'siswa')
                     ->get();
        
        return response()->json($siswa);
    }

    // 2. Input Absensi Manual (Flowchart: Hadir / Alpa / Sakit / Izin)
    public function storeManual(Request $request)
    {
        $request->validate([
            'jadwal_id' => 'required|exists:jadwals,id',
            'absensi_data' => 'required|array', // Array berisi siswa_id dan status
        ]);

        foreach ($request->absensi_data as $data) {
            Absensi::updateOrCreate(
                [
                    'siswa_id' => $data['siswa_id'],
                    'jadwal_id' => $request->jadwal_id,
                    'tanggal' => now()->toDateString(),
                ],
                [
                    'status' => $data['status'], // H, A, S, I
                    'metode' => 'Manual',
                    'waktu_absen' => now(),
                ]
            );
        }

        return response()->json(['message' => 'Absensi manual berhasil disimpan!']);
    }

    // 3. Kirim Pengumuman (Sesuai Use Case: UC_Pengumuman)
    public function kirimPengumuman(Request $request)
    {
        $request->validate([
            'id_kelas' => 'required|exists:kelas,id',
            'judul'    => 'required|string',
            'isi'      => 'required|string',
        ]);

        $pengumuman = Pengumuman::create([
            'id_guru'  => auth()->id(),
            'id_kelas' => $request->id_kelas,
            'judul'    => $request->judul,
            'isi'      => $request->isi,
            'tanggal'  => now(),
        ]);

        return response()->json(['message' => 'Pengumuman berhasil dikirim!', 'data' => $pengumuman]);
    }
}