<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Absensi extends Model
{
    protected $table = 'absensis'; 

    protected $fillable = [
        'siswa_id',    // Pastikan ini sesuai database
        'jadwal_id',   // Pastikan ini sesuai database
        'tanggal',
        'status',
        'metode',
        'latitude',
        'longitude',
        'waktu_absen',
        'dikoreksi_oleh'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'siswa_id');
    }

    // Tambahkan relasi ini untuk memperbaiki error "undefined relationship [jadwal]"
    public function jadwal(): BelongsTo
    {
        return $this->belongsTo(Jadwal::class, 'jadwal_id');
    }
}