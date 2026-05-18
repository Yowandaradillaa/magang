<?php

namespace App\Http\Controllers\API;

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
        // 1. Validasi Input (Hanya cek required & string, jangan cek format email!)
        $request->validate([
            'email'    => 'required|string', // 'email' di sini adalah nama key di Postman
            'password' => 'required',
        ]);

        $loginValue = $request->email;

        // 2. Deteksi otomatis kolom (NISN, NUPTK, atau Email)
        if (filter_var($loginValue, FILTER_VALIDATE_EMAIL)) {
            $field = 'email';
        } elseif (is_numeric($loginValue)) {
            $field = strlen($loginValue) <= 10 ? 'nisn' : 'nuptk';
        } else {
            $field = 'email';
        }

        // 3. Cari user di database
        $user = User::where($field, $loginValue)->first();

        // 4. Cek Password
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Kredensial salah. NISN/NUPTK atau password tidak cocok.'
            ], 422);
        }

        // 5. Buat Token Sanctum (Karena ini API)
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message'      => 'Login Berhasil!',
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'user'         => $user
        ]);
    }
}