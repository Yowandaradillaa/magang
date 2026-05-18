<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model
{
    protected $fillable = ['id_guru', 'id_kelas', 'judul', 'isi', 'tanggal'];
}
