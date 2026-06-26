<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // 1. Validasi Input (Gunakan login_id agar tidak rancu dengan email)
        $request->validate([
            'login_id' => 'required|string', 
            'password' => 'required',
        ]);

        $loginValue = $request->login_id;

        // 2. Deteksi otomatis kolom di database
        if (filter_var($loginValue, FILTER_VALIDATE_EMAIL)) {
            $field = 'email';
        } elseif (is_numeric($loginValue)) {
            // NISN (Siswa) biasanya 10 digit, NUPTK (Guru) biasanya 16 digit
            $field = strlen($loginValue) <= 10 ? 'nisn' : 'nuptk';
        } else {
            // Jika bukan email dan bukan angka, anggap email (untuk username admin jika ada)
            $field = 'email';
        }

        // 3. Proses Login
        if (!Auth::attempt([$field => $loginValue, 'password' => $request->password], $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'login_id' => 'Kredensial salah. ID Pengguna atau Password tidak cocok.',
            ]);
        }

        // 4. Sukses: Regenerate Session
        $request->session()->regenerate();

        $user = Auth::user();

        // 5. URL Redirect berdasarkan Role
        $url = match ($user->role) {
            'admin' => '/admin/dashboard',
            'guru'  => '/guru/dashboard',
            'siswa' => '/siswa/dashboard',
            default => '/',
        };

        // Respon JSON untuk API/Postman
        if ($request->wantsJson()) {
            return response()->json([
                'status'  => 'success',
                'message' => 'Login Berhasil!',
                'user'    => $user,
                'redirect_to' => $url
            ]);
        }

        return redirect()->intended($url);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password'     => 'required|min:6|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password lama salah!']);
        }

        $user->update(['password' => Hash::make($request->new_password)]);

        return back()->with('success', 'Password berhasil diperbarui!');
    }
}