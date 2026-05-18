<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Admin (Pake NUPTK)
        User::create([
            'name' => 'Admin Hadirify',
            'email' => 'admin@gmail.com',
            'nuptk' => '1234567890123456', // 16 digit
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // 2. Buat Guru (Pake NUPTK)
        User::create([
            'name' => 'Guru Joko',
            'nuptk' => '1122334455667788',
            'password' => Hash::make('password'),
            'role' => 'guru',
        ]);

        // 3. Buat Siswa (Pake NISN)
        User::create([
            'name' => 'Asep Kasep',
            'nisn' => '0011223344', // 10 digit
            'password' => Hash::make('password'),
            'role' => 'siswa',
        ]);
    }
}