<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Remedial extends Model
{
    use HasFactory;

    protected $table = 'remedial';

    protected $fillable = ['siswa_id', 'mapel_id', 'jenis', 'nilai_awal', 'nilai_akhir', 'tanggal', 'keterangan', 'semester'];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
        ];
    }

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }

    public function mapel(): BelongsTo
    {
        return $this->belongsTo(Mapel::class);
    }
}
