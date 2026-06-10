<?php

namespace App\Jobs;

use App\Models\Soal;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ImportSoalJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public array $data,
        public ?int $mapelId = null,
    ) {}

    public function handle(): void
    {
        foreach ($this->data as $row) {
            Soal::create([
                'mapel_id' => $this->mapelId ?? ($row['mapel_id'] ?? null),
                'tipe' => $row['tipe'] ?? 'PG',
                'kesulitan' => $row['kesulitan'] ?? 'Mudah',
                'konten' => $row['konten'],
                'jawaban' => $row['jawaban'],
                'opsi_a' => $row['opsi_a'] ?? null,
                'opsi_b' => $row['opsi_b'] ?? null,
                'opsi_c' => $row['opsi_c'] ?? null,
                'opsi_d' => $row['opsi_d'] ?? null,
                'opsi_e' => $row['opsi_e'] ?? null,
            ]);
        }
    }
}
