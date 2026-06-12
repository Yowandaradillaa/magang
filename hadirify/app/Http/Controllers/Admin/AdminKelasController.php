<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\User;
use App\Models\Jadwal;
use Illuminate\Http\Request;

class AdminKelasController extends Controller
{
    public function index()
    {
        $kelas = Kelas::with(['waliKelas', 'siswas'])->get();
        
        // --- HAPUS BARIS dd() DI SINI ---
        // Supaya kalau kelas kosong, halaman tetap kebuka dan kamu bisa klik "Tambah Kelas"
        
        $gurus = User::where('role', 'guru')->get(); 
        $jadwals = Jadwal::with(['kelas', 'mapel', 'guru'])->get();

        return view('admin.kelas', compact('kelas', 'gurus', 'jadwals'));
    }

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

    public function update(Request $request, $id)
    {
        $kelas = Kelas::findOrFail($id);
        
        $request->validate([
            'nama_kelas' => 'required|string',
            'tahun_ajaran' => 'required',
            'id_wali_kelas' => 'required|exists:users,id'
        ]);

        $kelas->update($request->all());

        return redirect()->back()->with('success', 'Data kelas berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $kelas = Kelas::findOrFail($id);
        $kelas->delete();

        return redirect()->back()->with('success', 'Kelas berhasil dihapus!');
    }
}