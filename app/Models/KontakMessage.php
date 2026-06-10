<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KontakMessage extends Model
{
    protected $table = 'kontak_messages';

    protected $fillable = ['nama', 'email', 'subjek', 'pesan', 'dibaca'];
}
