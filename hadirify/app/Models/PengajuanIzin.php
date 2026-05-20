<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PengajuanIzin extends Model
{
    protected $table = 'pengajuan_izins';

    protected $fillable = [
        'siswa_id', 
        'id_guru_approver', 
        'tanggal_mulai', 
        'tanggal_selesai', 
        'jenis', 
        'alasan', 
        'file_surat', 
        'status', 
        'catatan_guru', 
        'tanggal_pengajuan'
    ];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(User::class, 'siswa_id');
    }

    public function guru(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_guru_approver');
    }
}