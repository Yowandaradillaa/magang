<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'nisn',     // Untuk Siswa
        'nuptk',    // Untuk Guru/Admin
        'id_kelas', // Foreign Key ke tabel kelas (untuk Siswa)
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relasi: Siswa memiliki satu Kelas
     */
    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'id_kelas');
    }

    /**
     * Relasi: Guru bisa menjadi Wali Kelas di satu Kelas
     */
    public function waliDiKelas(): HasOne
    {
        return $this->hasOne(Kelas::class, 'id_wali_kelas');
    }

    /**
     * Relasi: User memiliki banyak catatan Absensi
     */
    public function absensis(): HasMany
    {
        return $this->hasMany(Absensi::class, 'siswa_id');
    }
}