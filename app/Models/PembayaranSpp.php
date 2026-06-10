<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PembayaranSpp extends Model
{
    use HasFactory;

    protected $table = 'pembayaran_spp';

    protected $fillable = [
        'siswa_id', 'bulan', 'tahun', 'jumlah', 'status', 'tanggal_bayar',
        'snap_token', 'payment_link', 'midtrans_transaction_id', 'midtrans_status',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_bayar' => 'date',
        ];
    }

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }

    public function scopeBelumLunas($query)
    {
        return $query->where('status', 'belum_lunas');
    }
}
