<?php

namespace App\Http\Controllers; // <-- Folder sudah bukan API lagi

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // 1. Validasi Input 
        $request->validate([
            'email'    => 'required|string', 
            'password' => 'required',
        ]);

        $loginValue = $request->email;

        // 2. Deteksi otomatis kolom (NISN, NUPTK, atau Email) buatan temanmu
        if (filter_var($loginValue, FILTER_VALIDATE_EMAIL)) {
            $field = 'email';
        } elseif (is_numeric($loginValue)) {
            $field = strlen($loginValue) <= 10 ? 'nisn' : 'nuptk';
        } else {
            $field = 'email';
        }

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
    }
}