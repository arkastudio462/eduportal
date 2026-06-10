<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class PeminjamanAset extends Model
{
    protected $table = 'peminjaman_aset';

    protected $fillable = ['peminjam_type', 'peminjam_id', 'ruang_id', 'barang_id', 'tujuan', 'tanggal_mulai', 'tanggal_selesai', 'status', 'keterangan', 'approved_by'];

    protected function casts(): array
    {
        return [
            'tanggal_mulai' => 'datetime',
            'tanggal_selesai' => 'datetime',
        ];
    }

    public function peminjam(): MorphTo
    {
        return $this->morphTo();
    }

    public function ruang(): BelongsTo
    {
        return $this->belongsTo(Ruang::class);
    }

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
