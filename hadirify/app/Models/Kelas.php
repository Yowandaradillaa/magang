<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kelas extends Model
{
    // Nama tabel jika tidak jamak (opsional, tapi disarankan jika di migrasi namanya 'kelas')
    protected $table = 'kelas';

    protected $fillable = [
        'nama_kelas', 
        'tahun_ajaran', 
        'id_wali_kelas'
    ];

    /**
     * Relasi: Kelas memiliki satu Wali Kelas (Guru)
     */
    public function waliKelas(): BelongsTo
    {
        // id_wali_kelas merujuk ke ID di tabel users
        return $this->belongsTo(User::class, 'id_wali_kelas');
    }

    /**
     * Relasi: Kelas memiliki banyak Siswa
     */
    public function siswas(): HasMany
    {
        return $this->hasMany(User::class, 'id_kelas');
    }

    /**
     * Relasi: Kelas memiliki banyak Jadwal Pelajaran
     */
    public function jadwals(): HasMany
    {
        return $this->hasMany(Jadwal::class, 'id_kelas');
    }
}