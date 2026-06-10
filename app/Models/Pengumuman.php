<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model
{
    use HasFactory;

    protected $table = 'pengumuman';

    protected $fillable = ['judul', 'konten', 'tanggal', 'tipe'];

    protected function casts(): array
    {
        return [
            'tanggal' => 'datetime',
        ];
    }
}
