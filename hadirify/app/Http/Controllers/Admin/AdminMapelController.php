<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MataPelajaran;
use App\Imports\MapelImport;
use App\Exports\MapelTemplateExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminMapelController extends Controller
{
    public function index() {
        $mapels = MataPelajaran::all();
        return view('admin.mapel', compact('mapels'));
    }

    /**
     * Unduh template file Excel untuk import Mata Pelajaran
     */
    public function downloadTemplate()
    {
        return Excel::download(new MapelTemplateExport(), 'template_import_mapel.xlsx');
    }

    /**
     * Import massal data Mata Pelajaran dari Excel/CSV
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:5120',
        ], [
            'file.required' => 'Silakan pilih file terlebih dahulu.',
            'file.mimes'    => 'Format file harus Excel (.xlsx / .xls) atau CSV.',
            'file.max'      => 'Ukuran file maksimal 5MB.',
        ]);

        $import = new MapelImport();
        Excel::import($import, $request->file('file'));

        $sukses = $import->getSuccessCount();
        $errors = $import->getErrors();

        if ($sukses > 0 && empty($errors)) {
            return redirect()->back()->with('success', "{$sukses} mata pelajaran berhasil ditambahkan dari file Excel!");
        }

        if ($sukses > 0 && !empty($errors)) {
            return redirect()->back()
                ->with('success', "{$sukses} mata pelajaran berhasil ditambahkan.")
                ->with('import_errors', $errors);
        }

        return redirect()->back()
            ->with('error', 'Tidak ada mata pelajaran yang berhasil ditambahkan. Cek detail error di bawah.')
            ->with('import_errors', $errors);
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