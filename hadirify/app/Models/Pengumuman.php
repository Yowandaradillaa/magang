<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pengumuman extends Model
{
    // Pakai nama tabel sesuai migration kamu
    protected $table = 'pengumumen';

    // Sesuaikan dengan nama kolom di migration (_id)
    protected $fillable = ['guru_id', 'kelas_id', 'judul', 'isi', 'tanggal'];

    public function guru(): BelongsTo
    {
        return $this->belongsTo(User::class, 'guru_id');
    }

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }
}