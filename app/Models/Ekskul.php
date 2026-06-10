<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Ekskul extends Model
{
    protected $table = 'ekskuls';

    protected $fillable = ['nama', 'pembina', 'hari', 'jam_mulai', 'jam_selesai', 'tempat', 'deskripsi', 'kuota', 'is_active'];

    protected function casts(): array
    {
        return [
            'jam_mulai' => 'datetime:H:i',
            'jam_selesai' => 'datetime:H:i',
            'is_active' => 'boolean',
        ];
    }

    public function anggota(): BelongsToMany
    {
        return $this->belongsToMany(Siswa::class, 'ekskul_anggota', 'ekskul_id', 'siswa_id')
            ->withPivot('status', 'keterangan')
            ->withTimestamps();
    }
}
