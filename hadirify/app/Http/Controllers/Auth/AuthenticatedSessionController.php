<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request)
    {
        // 1. Ambil nilai dari input 'email' (nama input di form blade kamu adalah 'email')
        $loginValue = $request->email;

        // 2. Tentukan field database berdasarkan format input
        if (filter_var($loginValue, FILTER_VALIDATE_EMAIL)) {
            $field = 'email';
        } elseif (is_numeric($loginValue)) {
            $field = strlen($loginValue) <= 10 ? 'nisn' : 'nuptk';
        } else {
            $field = 'email';
        }

        // 3. Proses Autentikasi Langsung (Manual Attempt)
        if (! Auth::attempt([$field => $loginValue, 'password' => $request->password], $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        // 4. Jika sukses, buat session baru
        $request->session()->regenerate();

        $user = Auth::user();

        // 5. Tentukan Redirect URL berdasarkan Role
        $url = match ($user->role) {
            'admin' => '/admin/dashboard',
            'guru'  => '/guru/dashboard',
            'siswa' => '/siswa/dashboard',
            default => '/',
        };

        // 6. Respon khusus untuk Postman (JSON) atau Browser (Redirect)
        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'message' => 'Login Berhasil!',
                'user' => $user,
                'role' => $user->role,
                'redirect_to' => $url
            ]);
        }

        return redirect()->intended($url);
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}