<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Soal extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'soal';

    protected $fillable = ['mapel_id', 'tipe', 'kesulitan', 'konten', 'gambar', 'opsi', 'jawaban'];

    public function mapel(): BelongsTo
    {
        return $this->belongsTo(Mapel::class);
    }
}
