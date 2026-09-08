<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MataPelajaran extends Model
{
    // Menyesuaikan dengan nama tabel di database MySQL kamu
    protected $table = 'mata_pelajarans';

    // Mengizinkan mass assignment untuk kolom-kolom ini
    protected $fillable = [
        'nama_mapel', 
        'kode_mapel', 
        'deskripsi'
    ];
}