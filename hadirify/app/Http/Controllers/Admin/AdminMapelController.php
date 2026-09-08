<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;

class AdminMapelController extends Controller
{
    public function index() {
        $mapels = MataPelajaran::all();
        return view('admin.mapel', compact('mapels'));
    }

    public function store(Request $request) {
        $request->validate([
            'nama_mapel' => 'required',
            'kode_mapel' => 'nullable',
            'deskripsi' => 'nullable'
        ]);
        MataPelajaran::create($request->all());
        return redirect()->back()->with('success', 'Mata pelajaran berhasil ditambah!');
    }

    public function update(Request $request, $id) {
        $request->validate([
            'nama_mapel' => 'required',
            'kode_mapel' => 'nullable',
            'deskripsi' => 'nullable'
        ]);

        $mapel = MataPelajaran::findOrFail($id);
        $mapel->update($request->all());

        return redirect()->back()->with('success', 'Mata pelajaran berhasil diperbarui!');
    }

    public function destroy($id) {
        MataPelajaran::destroy($id);
        return redirect()->back()->with('success', 'Mata pelajaran berhasil dihapus!');
    }
}