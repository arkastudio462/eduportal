<?php

namespace App\Observers;

use App\Models\PembayaranSpp;
use App\Models\Siswa;

class SiswaObserver
{
    public function created(Siswa $siswa): void
    {
        PembayaranSpp::create([
            'siswa_id' => $siswa->id,
            'bulan' => now()->format('m'),
            'tahun' => now()->format('Y'),
            'jumlah' => 750000,
            'status' => 'belum_lunas',
        ]);
    }
}
