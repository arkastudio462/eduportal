<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class EkskulAnggota extends Pivot
{
    protected $table = 'ekskul_anggota';

    protected $fillable = ['ekskul_id', 'siswa_id', 'status', 'keterangan'];

    public function ekskul(): BelongsTo
    {
        return $this->belongsTo(Ekskul::class);
    }

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }
}
