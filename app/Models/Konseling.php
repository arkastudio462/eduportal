<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Konseling extends Model
{
    use HasFactory;

    protected $table = 'konseling';

    protected $fillable = ['siswa_id', 'guru_id', 'tanggal', 'jam', 'topik', 'catatan', 'tindak_lanjut', 'status'];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'jam' => 'datetime:H:i',
        ];
    }

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }

    public function guru(): BelongsTo
    {
        return $this->belongsTo(Guru::class);
    }
}
