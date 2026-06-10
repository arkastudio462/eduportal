<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KiKd extends Model
{
    use HasFactory;

    protected $table = 'ki_kd';

    protected $fillable = ['mapel_id', 'kode', 'tipe', 'deskripsi', 'semester'];

    public function mapel(): BelongsTo
    {
        return $this->belongsTo(Mapel::class);
    }
}
