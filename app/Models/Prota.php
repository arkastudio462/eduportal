<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Prota extends Model
{
    use HasFactory;

    protected $table = 'prota';

    protected $fillable = ['mapel_id', 'tahun_ajaran', 'deskripsi', 'file'];

    public function mapel(): BelongsTo
    {
        return $this->belongsTo(Mapel::class);
    }

    public function promes(): HasMany
    {
        return $this->hasMany(Promes::class);
    }
}
