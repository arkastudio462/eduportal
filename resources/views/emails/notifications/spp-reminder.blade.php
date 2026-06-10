@php
    $siswa = $pembayaran->siswa->load('user', 'kelas');
    $bulan = \Carbon\Carbon::create()->month((int) $pembayaran->bulan)->locale('id')->monthName;
@endphp
<x-mail::message>
# Pengingat Pembayaran SPP

Yth. {{ $siswa->user->name }},

Pembayaran SPP periode **{{ $bulan }} {{ $pembayaran->tahun }}** belum dilakukan.

| Detail | Keterangan |
|:-------|:-----------|
| **Jumlah** | Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }} |
| **Status** | Belum Lunas |

<x-mail::button :url="route('portal-siswa.spp')">
Bayar Sekarang
</x-mail::button>

Terima kasih.<br>
{{ config('app.name') }}
</x-mail::message>
