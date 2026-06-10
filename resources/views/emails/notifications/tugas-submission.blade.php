<x-mail::message>
# Tugas Dikumpulkan

Siswa **{{ $namaSiswa }}** telah mengumpulkan tugas **{{ $judulTugas }}**.

<x-mail::button :url="route('portal-guru.tugas.submissions', $submission->tugas_id)">
Lihat Submission
</x-mail::button>

Salam,<br>
{{ config('app.name') }}
</x-mail::message>
