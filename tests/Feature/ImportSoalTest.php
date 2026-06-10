<?php

use App\Jobs\ImportSoalJob;
use App\Models\Mapel;
use App\Models\Soal;

it('ImportSoalJob creates soal records', function () {
    $mapel = Mapel::factory()->create();

    $rows = [
        ['tipe' => 'PG', 'kesulitan' => 'Mudah', 'konten' => '2+2=?', 'opsi_a' => '3', 'opsi_b' => '4', 'opsi_c' => '5', 'jawaban' => '4'],
        ['tipe' => 'Essay', 'kesulitan' => 'Sedang', 'konten' => 'Jelaskan hukum Newton', 'jawaban' => 'F=ma'],
    ];

    $job = new ImportSoalJob($rows, $mapel->id);
    $job->handle();

    expect(Soal::count())->toBe(2);
    expect(Soal::first()->mapel_id)->toBe($mapel->id);
});

it('ImportSoalJob handles empty rows', function () {
    $job = new ImportSoalJob([], null);
    $job->handle();

    expect(Soal::count())->toBe(0);
});
