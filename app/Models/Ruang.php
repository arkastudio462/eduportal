<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ruang extends Model
{
    protected $table = 'ruang';

    protected $fillable = ['kode', 'nama', 'lantai', 'gedung', 'kapasitas', 'jenis', 'keterangan', 'status'];

    public function barang(): HasMany
    {
        return $this->hasMany(Barang::class);
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
