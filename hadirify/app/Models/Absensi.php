<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Absensi extends Model
{
    protected $table = 'absensis'; 

    protected $fillable = [
        'siswa_id',    
        'jadwal_id',   
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

    // Tambahkan alias relasi 'siswa' agar cocok dengan controller dan view
    public function siswa(): BelongsTo
    {
        return $this->belongsTo(User::class, 'siswa_id');
    }

    public function jadwal(): BelongsTo
    {
        return $this->belongsTo(Jadwal::class, 'jadwal_id');
    }
}