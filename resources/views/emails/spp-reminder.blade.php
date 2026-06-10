@php
    $siswa = $pembayaran->siswa->load('user', 'kelas');
    $bulan = \Carbon\Carbon::create()->month((int) $pembayaran->bulan)->locale('id')->monthName;
@endphp
<x-mail::message>
# Pengingat Pembayaran SPP

Yth. {{ $siswa->user->name }},

Kami mengingatkan bahwa pembayaran SPP untuk periode **{{ $bulan }} {{ $pembayaran->tahun }}** belum dilakukan.

| Detail | Keterangan |
|:-------|:-----------|
| **Nama** | {{ $siswa->user->name }} |
| **NISN** | {{ $siswa->nisn }} |
| **Kelas** | {{ $siswa->kelas?->nama ?? '-' }} |
| **Periode** | {{ $bulan }} {{ $pembayaran->tahun }} |
| **Jumlah** | Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }} |

Segera lakukan pembayaran melalui portal siswa atau datang langsung ke Tata Usaha.

<x-mail::button :url="route('portal-siswa.spp')">
Bayar Sekarang
</x-mail::button>

Terima kasih.

Salam,<br>
{{ config('app.name') }}
</x-mail::message>
