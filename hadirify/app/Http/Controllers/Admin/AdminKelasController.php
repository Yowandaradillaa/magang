<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\User;
use App\Models\Jadwal;
use Illuminate\Http\Request;

class AdminKelasController extends Controller
{
    // Daftar Kelas beserta Wali Kelas dan Jadwalnya sekaligus
    public function index()
    {
        $kelas = Kelas::with(['waliKelas', 'siswas'])->get();
        if ($kelas->isEmpty()) {
        dd("Tabel kelas kosong! Tambahkan data lewat form atau database.");
    }
        $gurus = User::where('role', 'guru')->get(); // Untuk dropdown memilih wali kelas
        $jadwals = Jadwal::with(['kelas', 'mapel', 'guru'])->get(); // Mengisi tabel jadwal pelajaran

        return view('admin.kelas', compact('kelas', 'gurus', 'jadwals'));
    }

    // Buat Kelas Baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_kelas'    => 'required|string|unique:kelas,nama_kelas',
            'tahun_ajaran'  => 'required|string',
            'id_wali_kelas' => 'nullable|exists:users,id',
        ]);

        Kelas::create($request->all());

        return redirect()->back()->with('success', 'Kelas berhasil dibuat!');
    }

    // Update Kelas / Wali Kelas
    public function update(Request $request, $id)
    {
        $kelas = Kelas::findOrFail($id);
        $kelas->update($request->all());

        return redirect()->back()->with('success', 'Data kelas berhasil diperbarui!');
    }

    // Hapus Kelas
    public function destroy($id)
    {
        $kelas = Kelas::findOrFail($id);
        $kelas->delete();

        return redirect()->back()->with('success', 'Kelas berhasil dihapus!');
    }
}