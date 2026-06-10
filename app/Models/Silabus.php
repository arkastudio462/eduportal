<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Silabus extends Model
{
    use HasFactory;

    protected $table = 'silabus';

    protected $fillable = ['mapel_id', 'judul', 'deskripsi', 'file', 'semester'];

    public function mapel(): BelongsTo
    {
        return $this->belongsTo(Mapel::class);
    }
}
