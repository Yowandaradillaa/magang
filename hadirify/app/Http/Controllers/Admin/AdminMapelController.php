<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminMapelController extends Controller
{
    public function index() {
        $mapels = MataPelajaran::all();
        return view('admin.mapel', compact('mapels'));
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'nama_mapel' => ['required', 'string', 'max:255', Rule::unique('mata_pelajarans', 'nama_mapel')],
            'kode_mapel' => ['nullable', 'string', 'max:50', Rule::unique('mata_pelajarans', 'kode_mapel')],
            'deskripsi' => 'nullable|string',
        ], [
            'nama_mapel.unique' => 'Mata pelajaran dengan nama tersebut sudah terdaftar.',
            'kode_mapel.unique' => 'Kode mata pelajaran tersebut sudah digunakan.',
        ]);
        MataPelajaran::create($validated);
        return redirect()->back()->with('success', 'Mata pelajaran berhasil ditambah!');
    }

    public function update(Request $request, $id) {
        $mapel = MataPelajaran::findOrFail($id);
        $validated = $request->validate([
            'nama_mapel' => ['required', 'string', 'max:255', Rule::unique('mata_pelajarans', 'nama_mapel')->ignore($mapel->id)],
            'kode_mapel' => ['nullable', 'string', 'max:50', Rule::unique('mata_pelajarans', 'kode_mapel')->ignore($mapel->id)],
            'deskripsi' => 'nullable|string',
        ], [
            'nama_mapel.unique' => 'Mata pelajaran dengan nama tersebut sudah terdaftar.',
            'kode_mapel.unique' => 'Kode mata pelajaran tersebut sudah digunakan.',
        ]);
        $mapel->update($validated);

        return redirect()->back()->with('success', 'Mata pelajaran berhasil diperbarui!');
    }

    public function destroy($id) {
        MataPelajaran::destroy($id);
        return redirect()->back()->with('success', 'Mata pelajaran berhasil dihapus!');
    }
}