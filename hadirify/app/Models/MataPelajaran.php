<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MataPelajaran extends Model
{
    // Tambahkan baris ini biar nama_mapel boleh diisi:
    protected $fillable = ['nama_mapel', 'deskripsi'];
}