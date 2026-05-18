<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QRCode extends Model
{
    // Pastikan nama tabelnya benar (cek di database kamu q_r_codes atau qr_codes)
    protected $table = 'q_r_codes'; 

    protected $fillable = [
        'jadwal_id',    // Harus ada di sini!
        'guru_id',      // Harus ada di sini!
        'kode_qr',
        'waktu_dibuat',
        'waktu_expired',
        'status',
    ];
}