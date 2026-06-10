<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SertifikasiGuru extends Model
{
    protected $table = 'sertifikasi_guru';

    protected $fillable = [
        'guru_id', 'jenis_sertifikasi', 'nomor_sertifikat',
        'tahun_sertifikasi', 'bidang_studi', 'penerbit', 'file',
    ];

    public function guru(): BelongsTo
    {
        return $this->belongsTo(Guru::class);
    }
}
