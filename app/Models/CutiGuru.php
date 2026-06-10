<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CutiGuru extends Model
{
    protected $table = 'cuti_guru';

    protected $fillable = [
        'guru_id', 'jenis_cuti', 'tanggal_mulai', 'tanggal_selesai',
        'alasan', 'status', 'file', 'approved_by',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_mulai' => 'date',
            'tanggal_selesai' => 'date',
        ];
    }

    public function guru(): BelongsTo
    {
        return $this->belongsTo(Guru::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
