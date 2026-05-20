<?php

<<<<<<< HEAD
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
=======
namespace App\Http\Controllers; // <-- Folder sudah bukan API lagi

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // 1. Validasi Input 
>>>>>>> 0c8fa913973fc755b106b6b026a4b14f26e781e7
        $request->validate([
            'email'    => 'required|string', 
            'password' => 'required',
        ]);

        $loginValue = $request->email;

<<<<<<< HEAD
        // 2. Deteksi otomatis kolom (NISN, NUPTK, atau Email)
        if (filter_var($loginValue, FILTER_VALIDATE_EMAIL)) {
            $field = 'email';
        } elseif (is_numeric($loginValue)) {
            // NISN (Siswa) <= 10 digit, sisanya NUPTK (Guru/Admin)
=======
        // 2. Deteksi otomatis kolom (NISN, NUPTK, atau Email) buatan temanmu
        if (filter_var($loginValue, FILTER_VALIDATE_EMAIL)) {
            $field = 'email';
        } elseif (is_numeric($loginValue)) {
>>>>>>> 0c8fa913973fc755b106b6b026a4b14f26e781e7
            $field = strlen($loginValue) <= 10 ? 'nisn' : 'nuptk';
        } else {
            $field = 'email';
        }

<<<<<<< HEAD
        // 3. Coba Login menggunakan Session (Auth::attempt)
        // Kita gunakan Auth::attempt agar Laravel otomatis mengelola Session & Cookie
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
=======
        // 3. Proses Login Standar Web Laravel (Bukan Token API)
        if (Auth::attempt([$field => $loginValue, 'password' => $request->password])) {
            
            // Generate sesi baru (Wajib untuk keamanan Web biar nggak kena hack Session Fixation)
            $request->session()->regenerate();

            // Berhasil login? Langsung lempar ke rute /dashboard (Polisi Lalu Lintas kita)
            return redirect()->intended('/dashboard');
        }

        // 4. Kalau gagal login (password salah / akun nggak ada)
        return back()->withErrors([
            'email' => 'Kredensial salah. NISN/NUPTK atau password tidak cocok.',
        ])->onlyInput('email'); // onlyInput biar NISN yang diketik nggak hilang pas error
    }

    // 5. Sekalian aku buatkan fungsi Logout buat tombol di UI-mu!
    public function logout(Request $request)
    {
        Auth::logout();
        
        // Hapus tiket sesi di dompet browser (Chrome)
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Tendang balik ke halaman awal
        return redirect('/');
>>>>>>> 0c8fa913973fc755b106b6b026a4b14f26e781e7
    }
}