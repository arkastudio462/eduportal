<?php

namespace App\Jobs;

use App\Models\Absensi;
use App\Models\Nilai;
use App\Models\Siswa;
use App\Models\User;
use App\Notifications\ExportReadyNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

class ExportDataJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public string $type,
        public array $filters = [],
        public ?int $userId = null,
    ) {}

    public function handle(): string
    {
        $rows = match ($this->type) {
            'siswa' => $this->exportSiswa(),
            'nilai' => $this->exportNilai(),
            'absensi' => $this->exportAbsensi(),
            default => throw new \InvalidArgumentException("Unknown export type: {$this->type}"),
        };

        $csv = $this->arrayToCsv($rows);
        $path = 'exports/'.$this->type.'_'.now()->format('Ymd_His').'.csv';
        Storage::put($path, $csv);

        if ($this->userId) {
            $user = User::find($this->userId);
            if ($user) {
                Notification::send($user, new ExportReadyNotification($this->type, $path));
            }
        }

        return $path;
    }

    private function exportSiswa(): array
    {
        $query = Siswa::with('user', 'kelas');

        if (! empty($this->filters['kelas_id'])) {
            $query->where('kelas_id', $this->filters['kelas_id']);
        }

        if (! empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }

        $result = [['NISN', 'NIS', 'Nama', 'Email', 'Kelas', 'Status']];

        foreach ($query->cursor() as $siswa) {
            $result[] = [
                $siswa->nisn,
                $siswa->nis,
                $siswa->user->name,
                $siswa->user->email,
                $siswa->kelas?->nama,
                $siswa->status,
            ];
        }

        return $result;
    }

    private function exportNilai(): array
    {
        $query = Nilai::with('siswa.user', 'mapel');

        if (! empty($this->filters['mapel_id'])) {
            $query->where('mapel_id', $this->filters['mapel_id']);
        }

        if (! empty($this->filters['semester'])) {
            $query->where('semester', $this->filters['semester']);
        }

        $result = [['Nama', 'NISN', 'Mapel', 'Jenis', 'Semester', 'Skor']];

        foreach ($query->cursor() as $nilai) {
            $result[] = [
                $nilai->siswa?->user?->name,
                $nilai->siswa?->nisn,
                $nilai->mapel?->nama,
                $nilai->jenis,
                $nilai->semester,
                $nilai->skor,
            ];
        }

        return $result;
    }

    private function exportAbsensi(): array
    {
        $query = Absensi::with('siswa.user', 'jadwal');

        if (! empty($this->filters['tanggal_mulai'])) {
            $query->whereDate('tanggal', '>=', $this->filters['tanggal_mulai']);
        }

        if (! empty($this->filters['tanggal_selesai'])) {
            $query->whereDate('tanggal', '<=', $this->filters['tanggal_selesai']);
        }

        if (! empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }

        $result = [['Nama', 'NISN', 'Tanggal', 'Status', 'Keterangan']];

        foreach ($query->cursor() as $absensi) {
            $result[] = [
                $absensi->siswa?->user?->name,
                $absensi->siswa?->nisn,
                $absensi->tanggal?->format('Y-m-d'),
                $absensi->status,
                $absensi->keterangan,
            ];
        }

        return $result;
    }

    private function arrayToCsv(array $rows): string
    {
        $output = fopen('php://temp', 'r+');

        foreach ($rows as $row) {
            fputcsv($output, $row);
        }

        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);

        return $csv;
    }
}
