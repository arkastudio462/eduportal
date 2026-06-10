<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Barang extends Model
{
    protected $table = 'barang';

    protected $fillable = ['kode', 'nama', 'kategori', 'ruang_id', 'jumlah', 'kondisi', 'merek', 'tahun_peroleh', 'sumber_dana', 'keterangan'];

    public function ruang(): BelongsTo
    {
        return $this->belongsTo(Ruang::class);
    }

    public function peminjamanAset(): HasMany
    {
        return $this->hasMany(PeminjamanAset::class);
    }

    public function maintenanceAset(): HasMany
    {
        return $this->hasMany(MaintenanceAset::class);
    }
}
