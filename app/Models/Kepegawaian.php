<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Kepegawaian extends Model
{
    protected $table = 'kepegawaian';

    protected $fillable = [
        'guru_id', 'status_kepegawaian', 'golongan', 'jabatan',
        'tmt_cpns', 'tmt_pns', 'masa_kerja_tahun', 'masa_kerja_bulan',
        'nik', 'npwp', 'karpeg',
    ];

    protected function casts(): array
    {
        return [
            'tmt_cpns' => 'date',
            'tmt_pns' => 'date',
        ];
    }

    public function guru(): BelongsTo
    {
        return $this->belongsTo(Guru::class);
    }
}
