<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prestasi extends Model
{
    protected $table = 'prestasi';

    protected $fillable = ['judul', 'deskripsi', 'tingkat', 'peringkat', 'tanggal', 'tipe'];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
        ];
    }
}
