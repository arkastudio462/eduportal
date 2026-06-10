<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TracerStudy extends Model
{
    protected $table = 'tracer_study';

    protected $fillable = ['nama', 'tahun_lulus', 'pekerjaan', 'universitas', 'kontak', 'pesan'];
}
