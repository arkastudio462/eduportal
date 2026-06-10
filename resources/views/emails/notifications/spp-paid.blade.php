@php
    $siswa = $pembayaran->siswa->load('user', 'kelas');
    $bulan = \Carbon\Carbon::create()->month((int) $pembayaran->bulan)->locale('id')->monthName;
@endphp
<x-mail::message>
# Pembayaran SPP Diterima

Yth. {{ $siswa->user->name }},

Pembayaran SPP Anda telah diterima.

| Detail | Keterangan |
|:-------|:-----------|
| **Periode** | {{ $bulan }} {{ $pembayaran->tahun }} |
| **Jumlah** | Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }} |
| **Status** | Lunas |

<x-mail::button :url="route('portal-siswa.spp')">
Lihat Riwayat
</x-mail::button>

Terima kasih.<br>
{{ config('app.name') }}
</x-mail::message>
