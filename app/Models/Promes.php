<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Promes extends Model
{
    use HasFactory;

    protected $table = 'promes';

    protected $fillable = ['prota_id', 'bulan', 'materi', 'minggu_ke'];

    public function prota(): BelongsTo
    {
        return $this->belongsTo(Prota::class);
    }
}
