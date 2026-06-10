<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaintenanceAset extends Model
{
    protected $table = 'maintenance_aset';

    protected $fillable = ['barang_id', 'ruang_id', 'jenis', 'deskripsi', 'tanggal_mulai', 'tanggal_selesai', 'biaya', 'status', 'pelaksana', 'keterangan'];

    protected function casts(): array
    {
        return [
            'tanggal_mulai' => 'date',
            'tanggal_selesai' => 'date',
            'biaya' => 'decimal:2',
        ];
    }

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class);
    }

    public function ruang(): BelongsTo
    {
        return $this->belongsTo(Ruang::class);
    }
}
