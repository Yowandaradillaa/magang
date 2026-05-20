<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminUserController extends Controller
{
    // Tampilkan semua user ke halaman Blade Kelola Akun
    public function index()
    {
        $users = User::with('kelas')->get();
        $kelas = Kelas::all(); // Mengambil data kelas untuk dropdown pilih kelas saat tambah siswa
        
        return view('admin.akun', compact('users', 'kelas'));
    }

    // Simpan User Baru dari Form
    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'role'  => 'required|in:admin,guru,siswa',
            'email' => 'nullable|email|unique:users,email',
            'nisn'  => 'nullable|string|unique:users,nisn|max:10',
            'nuptk' => 'nullable|string|unique:users,nuptk|max:16',
            'id_kelas' => 'nullable|exists:kelas,id',
        ]);

        $passwordDefault = $request->role === 'siswa' ? $request->nisn : $request->nuptk;

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'role'     => $request->role,
            'nisn'     => $request->nisn,
            'nuptk'    => $request->nuptk,
            'id_kelas' => $request->id_kelas,
            'password' => Hash::make($passwordDefault ?? 'password123'),
        ]);

        return redirect()->back()->with('success', 'Akun berhasil dibuat!');
    }

    // Update Data User
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'  => 'string|max:255',
            'email' => ['nullable', 'email', Rule::unique('users')->ignore($user->id)],
            'nisn'  => ['nullable', 'string', Rule::unique('users')->ignore($user->id)],
            'nuptk' => ['nullable', 'string', Rule::unique('users')->ignore($user->id)],
            'id_kelas' => 'nullable|exists:kelas,id',
        ]);

        $user->update($request->all());

        return redirect()->back()->with('success', 'Data akun berhasil diperbarui!');
    }

    // Hapus User
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->back()->with('success', 'Akun berhasil dihapus!');
    }

    // Reset Password ke Default (NISN/NUPTK)
    public function resetPassword($id)
    {
        $user = User::findOrFail($id);
        $newPassword = $user->role === 'siswa' ? $user->nisn : $user->nuptk;

        if (!$newPassword) {
            return redirect()->back()->with('error', 'Gagal! User tidak memiliki NISN/NUPTK');
        }

        $user->update([
            'password' => Hash::make($newPassword)
        ]);

        return redirect()->back()->with('success', "Password {$user->name} berhasil direset!");
    }
}