<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminUserController extends Controller
{
    // Tampilkan semua user (Siswa, Guru, Admin)
    public function index()
    {
        $users = User::with('kelas')->get();
        return response()->json($users);
    }

    // Simpan User Baru (Flowchart: Tambah Akun)
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

        // Sesuai Flowchart: Password default saat buat akun adalah NISN/NUPTK
        $passwordDefault = $request->role === 'siswa' ? $request->nisn : $request->nuptk;

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'role'     => $request->role,
            'nisn'     => $request->nisn,
            'nuptk'    => $request->nuptk,
            'id_kelas' => $request->id_kelas,
            'password' => Hash::make($passwordDefault ?? 'password123'),
        ]);

        return response()->json(['message' => 'Akun berhasil dibuat!', 'user' => $user]);
    }

    // Update Data User (Flowchart: Edit Akun)
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

        return response()->json(['message' => 'Data akun berhasil diperbarui', 'user' => $user]);
    }

    // Hapus User (Flowchart: Hapus Akun)
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'Akun berhasil dihapus']);
    }

    // Reset Password (Flowchart: Reset Password ke NISN/NUPTK)
    public function resetPassword($id)
    {
        $user = User::findOrFail($id);
        
        // Tentukan password default berdasarkan role
        $newPassword = $user->role === 'siswa' ? $user->nisn : $user->nuptk;

        if (!$newPassword) {
            return response()->json(['message' => 'Gagal! User tidak memiliki NISN/NUPTK'], 422);
        }

        $user->update([
            'password' => Hash::make($newPassword)
        ]);

        return response()->json(['message' => "Password {$user->name} berhasil direset ke default!"]);
    }
}