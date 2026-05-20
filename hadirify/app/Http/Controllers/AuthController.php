<?php

namespace App\Http\Controllers; // Namespace diperbarui (hapus \API)

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Menangani proses Login untuk Web & Postman (Fullstack).
     */
    public function login(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'email'    => 'required|string', 
            'password' => 'required',
        ]);

        $loginValue = $request->email;

        // 2. Deteksi otomatis kolom (NISN, NUPTK, atau Email)
        if (filter_var($loginValue, FILTER_VALIDATE_EMAIL)) {
            $field = 'email';
        } elseif (is_numeric($loginValue)) {
            // NISN (Siswa) <= 10 digit, sisanya NUPTK (Guru/Admin)
            $field = strlen($loginValue) <= 10 ? 'nisn' : 'nuptk';
        } else {
            $field = 'email';
        }

        // 3. Coba Login menggunakan Session (Auth::attempt)
        if (!Auth::attempt([$field => $loginValue, 'password' => $request->password], $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => 'Kredensial salah. NISN/NUPTK atau password tidak cocok.',
            ]);
        }

        // 4. Jika sukses, buat ulang session (keamanan)
        $request->session()->regenerate();

        $user = Auth::user();

        // 5. Tentukan URL Redirect berdasarkan Role
        $url = match ($user->role) {
            'admin' => '/admin/dashboard',
            'guru'  => '/guru/dashboard',
            'siswa' => '/siswa/dashboard',
            default => '/',
        };

        // 6. Respon JSON jika lewat Postman, Redirect jika lewat Browser
        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Login Berhasil!',
                'user'    => $user,
                'redirect_to' => $url
            ]);
        }

        return redirect()->intended($url);
    }

    /**
     * Logout untuk sistem Fullstack.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    /**
     * Fitur Ganti Password (sesuai Use Case).
     */
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

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return back()->with('success', 'Password berhasil diperbarui!');
    }
}