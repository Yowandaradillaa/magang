<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Absensi;
use Illuminate\Http\Request;

class AdminJadwalController extends Controller
{
    public function index() {
        $jadwals = Jadwal::with(['kelas', 'mapel', 'guru'])->get();
        return view('admin.kelas', compact('jadwals'));
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

        Jadwal::create($request->all());
        return redirect()->back()->with('success', 'Jadwal berhasil dibuat!');
    }

    // Koreksi Absensi Siswa oleh Admin
    public function koreksiAbsen(Request $request, $idAbsensi) {
        $request->validate([
            'status' => 'required|in:H,A,S,I',
        ]);

        $absen = Absensi::findOrFail($idAbsensi);
        $absen->update([
            'status' => $request->status,
            'dikoreksi_oleh' => auth()->id() 
        ]);

        return redirect()->back()->with('success', 'Absensi berhasil dikoreksi oleh Admin!');
    }
}