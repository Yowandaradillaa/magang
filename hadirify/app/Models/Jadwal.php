<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Jadwal extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_kelas',
        'id_mapel',
        'id_guru',
        'hari',
        'jam_mulai',
        'jam_selesai',
    ];

    /**
     * Relasi ke Kelas
     */
    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'id_kelas');
    }

    /**
     * Relasi ke Mata Pelajaran
     */
    public function mapel(): BelongsTo
    {
        return $this->belongsTo(MataPelajaran::class, 'id_mapel');
    }

    /**
     * Relasi ke Guru (User)
     */
    public function guru(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_guru');
    }
}