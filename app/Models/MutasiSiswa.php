<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MutasiSiswa extends Model
{
    use HasFactory;

    protected $table = 'mutasi_siswa';

    protected $fillable = ['siswa_id', 'jenis', 'tanggal', 'sekolah_asal', 'sekolah_tujuan', 'alasan', 'keterangan'];

    protected function casts(): array
    {
        return ['tanggal' => 'date'];
    }

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }
}
