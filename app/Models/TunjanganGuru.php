<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TunjanganGuru extends Model
{
    protected $table = 'tunjangan_guru';

    protected $fillable = [
        'guru_id', 'jenis_tunjangan', 'besaran', 'periode_bulan',
        'periode_tahun', 'status', 'tanggal_bayar', 'keterangan',
    ];

    protected function casts(): array
    {
        return [
            'besaran' => 'decimal:2',
            'tanggal_bayar' => 'date',
        ];
    }

    public function guru(): BelongsTo
    {
        return $this->belongsTo(Guru::class);
    }
}
