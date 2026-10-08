<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\User;
use App\Models\Jadwal;
use Illuminate\Http\Request;
use App\Models\MataPelajaran;
use Illuminate\Validation\Rule;

class AdminKelasController extends Controller
{
    public function index()
    {
        $kelas = Kelas::with(['waliKelas', 'siswas'])->get();
        
        $gurus = User::where('role', 'guru')->get(); 
        $jadwals = Jadwal::with(['kelas', 'mapel', 'guru'])->get();
        $mapels = MataPelajaran::all();

        return view('admin.kelas', compact('kelas', 'gurus', 'jadwals', 'mapels'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kelas'    => ['required', 'string', 'max:255', Rule::unique('kelas', 'nama_kelas')->where('tahun_ajaran', $request->input('tahun_ajaran'))],
            'mata_pelajaran' => 'nullable|string|max:255',
            'tahun_ajaran'  => 'required|string|max:20',
            'id_wali_kelas' => ['nullable', Rule::exists('users', 'id')->where('role', 'guru')],
        ], [
            'nama_kelas.unique' => 'Kelas dengan nama dan tahun ajaran tersebut sudah terdaftar.',
        ]);

        Kelas::create($validated);

        return redirect()->back()->with('success', 'Kelas berhasil dibuat!');
    }

    public function update(Request $request, $id)
    {
        $kelas = Kelas::findOrFail($id);
        
        $validated = $request->validate([
            'nama_kelas' => ['required', 'string', 'max:255', Rule::unique('kelas', 'nama_kelas')
                ->where('tahun_ajaran', $request->input('tahun_ajaran'))
                ->ignore($kelas->id)],
            'mata_pelajaran' => 'nullable|string|max:255',
            'tahun_ajaran' => 'required|string|max:20',
            'id_wali_kelas' => ['required', Rule::exists('users', 'id')->where('role', 'guru')],
        ], [
            'nama_kelas.unique' => 'Kelas dengan nama dan tahun ajaran tersebut sudah terdaftar.',
        ]);

        $kelas->update($validated);

        return redirect()->back()->with('success', 'Data kelas berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $kelas = Kelas::findOrFail($id);
        $kelas->delete();

        return redirect()->back()->with('success', 'Kelas berhasil dihapus!');
    }
}