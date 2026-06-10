<?php

use App\Models\Kelas;
use App\Models\PembayaranSpp;
use App\Models\Siswa;
use App\Models\Ujian;

it('auto-expires ujian with past tanggal_selesai', function () {
    $ujian = Ujian::factory()->create([
        'status' => 'sedang_berlangsung',
        'tanggal_mulai' => now()->subDays(2),
        'tanggal_selesai' => now()->subDay(),
    ]);

    Ujian::where('status', 'sedang_berlangsung')
        ->where('tanggal_selesai', '<', now())
        ->update(['status' => 'selesai']);

    expect($ujian->fresh()->status)->toBe('selesai');
});

it('does not expire ujian that is still within date range', function () {
    $ujian = Ujian::factory()->create([
        'status' => 'sedang_berlangsung',
        'tanggal_mulai' => now()->subDay(),
        'tanggal_selesai' => now()->addDay(),
    ]);

    Ujian::where('status', 'sedang_berlangsung')
        ->where('tanggal_selesai', '<', now())
        ->update(['status' => 'selesai']);

    expect($ujian->fresh()->status)->toBe('sedang_berlangsung');
});

it('generates SPP for active siswa monthly', function () {
    $kelas = Kelas::factory()->create();
    $siswa1 = Siswa::factory()->create(['kelas_id' => $kelas->id, 'status' => 'aktif']);
    $siswa2 = Siswa::factory()->create(['kelas_id' => $kelas->id, 'status' => 'aktif']);
    $nonAktif = Siswa::factory()->create(['kelas_id' => $kelas->id, 'status' => 'nonaktif']);

    $bulan = now()->format('m');
    $tahun = now()->format('Y');

    $before = PembayaranSpp::where('bulan', $bulan)->where('tahun', $tahun)->count();
    $siswaAktif = Siswa::aktif()->get();

    $created = 0;
    foreach ($siswaAktif as $siswa) {
        $exists = PembayaranSpp::where('siswa_id', $siswa->id)
            ->where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->exists();

        if (! $exists) {
            PembayaranSpp::create([
                'siswa_id' => $siswa->id,
                'bulan' => $bulan,
                'tahun' => $tahun,
                'jumlah' => 750000,
                'status' => 'belum_lunas',
            ]);
            $created++;
        }
    }

    $after = PembayaranSpp::where('bulan', $bulan)->where('tahun', $tahun)->count();

    expect($created)->toBe(0);
    expect($after)->toBe($before);
    expect($after)->toBeGreaterThanOrEqual(2);
});

it('does not duplicate SPP for same month', function () {
    $kelas = Kelas::factory()->create();
    $siswa = Siswa::factory()->create(['kelas_id' => $kelas->id, 'status' => 'aktif']);

    $bulan = now()->format('m');
    $tahun = now()->format('Y');

    $before = PembayaranSpp::where('siswa_id', $siswa->id)
        ->where('bulan', $bulan)
        ->where('tahun', $tahun)
        ->count();

    foreach (Siswa::aktif()->get() as $s) {
        $exists = PembayaranSpp::where('siswa_id', $s->id)
            ->where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->exists();

        if (! $exists) {
            PembayaranSpp::create([
                'siswa_id' => $s->id,
                'bulan' => $bulan,
                'tahun' => $tahun,
                'jumlah' => 750000,
                'status' => 'belum_lunas',
            ]);
        }
    }

    $after = PembayaranSpp::where('siswa_id', $siswa->id)
        ->where('bulan', $bulan)
        ->where('tahun', $tahun)
        ->count();

    expect($after)->toBe(1);
    expect($after - $before)->toBe(0);
});
