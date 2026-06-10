<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Siswa extends Model
{
    use HasFactory, SoftDeletes;

    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('status', 'aktif');
    }

    protected $table = 'siswa';

    protected $fillable = ['user_id', 'nisn', 'nis', 'kelas_id', 'status',
        'nama_ayah', 'nama_ibu', 'no_wa_ayah', 'no_wa_ibu', 'email_ayah', 'email_ibu',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }

    public function nilai(): HasMany
    {
        return $this->hasMany(Nilai::class);
    }

    public function pembayaranSpp(): HasMany
    {
        return $this->hasMany(PembayaranSpp::class);
    }

    public function absensi(): HasMany
    {
        return $this->hasMany(Absensi::class);
    }

    public function peminjaman(): HasMany
    {
        return $this->hasMany(Peminjaman::class);
    }

    public function tugasSubmissions(): HasMany
    {
        return $this->hasMany(TugasSubmission::class);
    }

    public function berkas(): HasMany
    {
        return $this->hasMany(SiswaBerkas::class);
    }

    public function ekskuls(): BelongsToMany
    {
        return $this->belongsToMany(Ekskul::class, 'ekskul_anggota', 'siswa_id', 'ekskul_id')
            ->withPivot('status', 'keterangan')
            ->withTimestamps();
    }
}
