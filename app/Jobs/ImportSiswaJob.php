<?php

namespace App\Jobs;

use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Hash;

class ImportSiswaJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public array $rows,
    ) {}

    public function handle(): void
    {
        foreach ($this->rows as $row) {
            if (empty($row['nama']) || empty($row['nisn'])) {
                continue;
            }

            $kelas = null;
            if (! empty($row['kelas'])) {
                $kelas = Kelas::where('nama', $row['kelas'])->first();
            }

            $user = User::firstOrCreate(
                ['email' => $row['email'] ?? $row['nisn'].'@example.com'],
                [
                    'name' => $row['nama'],
                    'password' => Hash::make('password'),
                    'role' => 'siswa',
                ]
            );

            Siswa::updateOrCreate(
                ['nisn' => $row['nisn']],
                [
                    'user_id' => $user->id,
                    'nis' => $row['nis'] ?? $row['nisn'],
                    'kelas_id' => $kelas?->id,
                    'status' => $row['status'] ?? 'aktif',
                ]
            );
        }
    }
}
