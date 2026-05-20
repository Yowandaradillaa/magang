<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;

class AdminMapelController extends Controller
{
    public function index() {
        $mapels = MataPelajaran::all();
        return view('admin.kelas', compact('mapels'));
    }

    public function store(Request $request) {
        $request->validate(['nama_mapel' => 'required|unique:mata_pelajarans']);
        MataPelajaran::create($request->all());
        return redirect()->back()->with('success', 'Mata pelajaran berhasil ditambah!');
    }

    public function destroy($id) {
        MataPelajaran::destroy($id);
        return redirect()->back()->with('success', 'Mata pelajaran berhasil dihapus!');
    }
}