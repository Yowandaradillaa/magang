<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Absensi;
use App\Models\User;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;

class AdminJadwalController extends Controller
{
    public function index() {
    $jadwals = Jadwal::with(['kelas', 'mapel', 'guru'])->get();
    $kelas = Kelas::all();
    $gurus = User::where('role', 'guru')->get();
    $mapels = MataPelajaran::all();

    return view('admin.kelas', compact('jadwals', 'kelas', 'gurus', 'mapels'));
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

    public function destroy($id) {
    Jadwal::findOrFail($id)->delete();
    return redirect()->back()->with('success', 'Jadwal berhasil dihapus!');
}
    // Menampilkan halaman Koreksi Absensi dan hasil pencarian
    public function koreksiIndex(Request $request) 
    {
        $search = $request->input('search');

        $absensis = Absensi::with(['siswa', 'jadwal.mapel', 'siswa.kelas'])
            ->when($search, function ($query, $search) {
                return $query->whereHas('siswa', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('nisn', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->get();

        return view('admin.koreksi', compact('absensis'));
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