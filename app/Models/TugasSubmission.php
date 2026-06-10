<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TugasSubmission extends Model
{
    protected $table = 'tugas_submissions';

    protected $fillable = ['tugas_id', 'siswa_id', 'file', 'catatan', 'nilai'];

    public function tugas(): BelongsTo
    {
        return $this->belongsTo(Tugas::class);
    }

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }
}
