<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use Illuminate\Http\Request;

class AdminKelasController extends Controller
{
    // Daftar Kelas beserta Wali Kelasnya
    public function index()
    {
        $kelas = Kelas::with(['waliKelas', 'siswas'])->get();
        return response()->json($kelas);
    }

    // Buat Kelas Baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_kelas'    => 'required|string|unique:kelas,nama_kelas',
            'tahun_ajaran'  => 'required|string',
            'id_wali_kelas' => 'nullable|exists:users,id', // Harus ID user dengan role guru
        ]);

        $kelas = Kelas::create($request->all());

        return response()->json(['message' => 'Kelas berhasil dibuat', 'data' => $kelas]);
    }

    // Update Kelas / Wali Kelas
    public function update(Request $request, $id)
    {
        $kelas = Kelas::findOrFail($id);
        $kelas->update($request->all());

        return response()->json(['message' => 'Data kelas berhasil diperbarui', 'data' => $kelas]);
    }

    // Hapus Kelas
    public function destroy($id)
    {
        $kelas = Kelas::findOrFail($id);
        $kelas->delete();

        return response()->json(['message' => 'Kelas berhasil dihapus']);
    }
}