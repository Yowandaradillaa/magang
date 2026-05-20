<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QRCode extends Model
{
    // Nama tabel di database (hasil migrasi Laravel untuk QRCode)
    protected $table = 'q_r_codes'; 

    protected $fillable = [
        'jadwal_id',    // Wajib ada agar data bisa masuk
        'guru_id',      // Wajib ada agar data bisa masuk
        'kode_qr',
        'waktu_dibuat',
        'waktu_expired',
        'status',
    ];

    // Aktifkan ini jika tabel kamu punya created_at & updated_at
    public $timestamps = true;
}