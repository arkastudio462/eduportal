<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PresensiGuru extends Model
{
    use HasFactory;

    protected $table = 'presensi_guru';

    protected $fillable = ['guru_id', 'tanggal', 'check_in', 'check_out', 'status', 'keterangan', 'qr_token'];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'check_in' => 'datetime:H:i',
            'check_out' => 'datetime:H:i',
        ];
    }

    public function guru(): BelongsTo
    {
        return $this->belongsTo(Guru::class);
    }
}
