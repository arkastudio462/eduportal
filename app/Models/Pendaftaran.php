<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pendaftaran extends Model
{
    protected $table = 'pendaftaran';

    public function getRouteKeyName(): string
    {
        return 'no_pendaftaran';
    }

    protected $fillable = [
        'no_pendaftaran', 'nama_lengkap', 'nisn', 'tempat_lahir', 'tanggal_lahir',
        'jenis_kelamin', 'agama', 'alamat', 'no_hp', 'email', 'asal_sekolah',
        'jurusan_daftar', 'nilai_rata_rata', 'nama_ayah', 'nama_ibu',
        'no_hp_ayah', 'no_hp_ibu', 'pekerjaan_ayah', 'pekerjaan_ibu',
        'status', 'catatan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
        ];
    }

    public function berkas(): HasMany
    {
        return $this->hasMany(BerkasPendaftaran::class);
    }
}
