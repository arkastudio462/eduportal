<x-mail::message>
# Pengumuman

**{{ $pengumuman->judul }}**

{{ Str::limit($pengumuman->konten, 500) }}

<x-mail::button :url="route('admin.pengumuman')">
Lihat Detail
</x-mail::button>

Salam,<br>
{{ config('app.name') }}
</x-mail::message>
