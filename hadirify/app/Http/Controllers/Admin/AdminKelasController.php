<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\User;
use App\Models\Jadwal;
use App\Models\MataPelajaran;
use App\Imports\KelasImport;
use App\Exports\KelasTemplateExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
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

    /**
     * Unduh template file Excel untuk import Kelas
     */
    public function downloadTemplate()
    {
        return Excel::download(new KelasTemplateExport(), 'template_import_kelas.xlsx');
    }

    /**
     * Import massal data Kelas dari Excel/CSV
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

        $import = new KelasImport();
        Excel::import($import, $request->file('file'));

        $sukses = $import->getSuccessCount();
        $errors = $import->getErrors();

        if ($sukses > 0 && empty($errors)) {
            return redirect()->back()->with('success', "{$sukses} kelas berhasil ditambahkan dari file Excel!");
        }

        if ($sukses > 0 && !empty($errors)) {
            return redirect()->back()
                ->with('success', "{$sukses} kelas berhasil ditambahkan.")
                ->with('import_errors', $errors);
        }

        return redirect()->back()
            ->with('error', 'Tidak ada kelas yang berhasil ditambahkan. Cek detail error di bawah.')
            ->with('import_errors', $errors);
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