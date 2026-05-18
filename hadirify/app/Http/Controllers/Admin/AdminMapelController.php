<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;

class AdminMapelController extends Controller
{
    public function index() {
        return response()->json(MataPelajaran::all());
    }

    public function store(Request $request) {
        $request->validate(['nama_mapel' => 'required|unique:mata_pelajarans']);
        $mapel = MataPelajaran::create($request->all());
        return response()->json(['message' => 'Mapel berhasil ditambah', 'data' => $mapel]);
    }

    public function destroy($id) {
        MataPelajaran::destroy($id);
        return response()->json(['message' => 'Mapel berhasil dihapus']);
    }
}