<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Absensi;
use Illuminate\Http\Request;

class AdminJadwalController extends Controller
{
    public function index() {
        // Lihat semua jadwal lengkap dengan relasinya
        return response()->json(Jadwal::with(['kelas', 'mapel', 'guru'])->get());
    }

    public function store(Request $request) {
        $request->validate([
            'id_kelas' => 'required',
            'id_mapel' => 'required',
            'id_guru' => 'required',
            'hari' => 'required',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
        ]);

        $jadwal = Jadwal::create($request->all());
        return response()->json(['message' => 'Jadwal berhasil dibuat', 'data' => $jadwal]);
    }

    // --- FITUR KOREKSI ABSENSI (Sesuai Flowchart) ---
    public function koreksiAbsen(Request $request, $idAbsensi) {
        $request->validate([
            'status' => 'required|in:H,A,S,I',
        ]);

        $absen = Absensi::findOrFail($idAbsensi);
        $absen->update([
            'status' => $request->status,
            'dikoreksi_oleh' => auth()->id() // Mencatat siapa admin yang koreksi
        ]);

        return response()->json(['message' => 'Absensi berhasil dikoreksi oleh Admin']);
    }
}