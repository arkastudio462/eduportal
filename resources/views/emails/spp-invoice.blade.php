@php
    $siswa = $pembayaran->siswa->load('user', 'kelas');
@endphp
<x-mail::message>
# Invoice Pembayaran SPP

Yth. {{ $siswa->user->name }},

Pembayaran SPP Anda telah kami terima. Berikut detail pembayaran:

| Detail | Keterangan |
|:-------|:-----------|
| **Nama** | {{ $siswa->user->name }} |
| **NISN** | {{ $siswa->nisn }} |
| **Kelas** | {{ $siswa->kelas?->nama ?? '-' }} |
| **Periode** | {{ $bulan }} {{ $pembayaran->tahun }} |
| **Jumlah** | Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }} |
| **Status** | Lunas |

Invoice PDF terlampir pada email ini.

<x-mail::button :url="route('portal-siswa.spp')">
Lihat Riwayat Pembayaran
</x-mail::button>

Terima kasih.

Salam,<br>
{{ config('app.name') }}
</x-mail::message>
