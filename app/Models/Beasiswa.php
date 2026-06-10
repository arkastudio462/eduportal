<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Beasiswa extends Model
{
    use HasFactory;

    protected $table = 'beasiswa';

    protected $fillable = ['siswa_id', 'nama_beasiswa', 'penyelenggara', 'tahun', 'keterangan', 'file'];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }
}
