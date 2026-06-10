<x-mail::message>
# Tugas Baru

Yth. Siswa,

Tugas baru telah diberikan:

**{{ $tugas->judul }}**

Mapel: {{ $tugas->mapel?->nama ?? '-' }}
Batas Pengumpulan: {{ $tugas->tenggat?->isoFormat('D MMM YYYY HH:mm') ?? '-' }}

<x-mail::button :url="route('portal-siswa.tugas')">
Lihat Tugas
</x-mail::button>

Selamat mengerjakan!

Salam,<br>
{{ config('app.name') }}
</x-mail::message>
