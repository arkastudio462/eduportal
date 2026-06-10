<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tugas extends Model
{
    protected $table = 'tugas';

    protected $fillable = ['guru_id', 'mapel_id', 'kelas_id', 'judul', 'deskripsi', 'deadline', 'file'];

    protected function casts(): array
    {
        return ['deadline' => 'datetime'];
    }

    public function guru(): BelongsTo
    {
        return $this->belongsTo(Guru::class);
    }

    public function mapel(): BelongsTo
    {
        return $this->belongsTo(Mapel::class);
    }

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(TugasSubmission::class);
    }
}
