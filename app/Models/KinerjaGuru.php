<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KinerjaGuru extends Model
{
    protected $table = 'kinerja_guru';

    protected $fillable = [
        'guru_id', 'tahun_ajaran', 'semester', 'jam_mengajar_per_minggu',
        'skor_pkg', 'predikat_pkg', 'kategori', 'catatan',
    ];

    public function guru(): BelongsTo
    {
        return $this->belongsTo(Guru::class);
    }
}
