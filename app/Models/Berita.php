<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Berita extends Model
{
    use Concerns\HasSlug;
    use HasFactory, SoftDeletes;

    protected $table = 'beritas';

    protected $fillable = ['judul', 'slug', 'konten', 'kategori', 'gambar', 'is_utama', 'tanggal'];

    protected function casts(): array
    {
        return ['tanggal' => 'datetime', 'is_utama' => 'boolean'];
    }
}
