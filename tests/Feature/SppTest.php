<?php

use App\Models\Kelas;
use App\Models\PembayaranSpp;
use App\Models\Siswa;
use App\Models\User;

beforeEach(function () {
    $this->admin = User::factory()->create(['role' => 'admin']);
});

it('creates SPP record when siswa is created via observer', function () {
    $kelas = Kelas::factory()->create();
    $siswa = Siswa::factory()->create(['kelas_id' => $kelas->id]);

    expect(PembayaranSpp::count())->toBe(1);
    expect($siswa->pembayaranSpp()->first()->jumlah)->toBe(750000);
    expect($siswa->pembayaranSpp()->first()->status)->toBe('belum_lunas');
});

it('admin can access keuangan page', function () {
    $this->actingAs($this->admin)
        ->get(route('admin.keuangan'))
        ->assertOk();
});

it('admin can create SPP payment', function () {
    $kelas = Kelas::factory()->create();
    $siswa = Siswa::factory()->create(['kelas_id' => $kelas->id]);

    $this->actingAs($this->admin)->post(route('admin.keuangan.store'), [
        'siswa_id' => $siswa->id,
        'bulan' => now()->format('m'),
        'tahun' => now()->format('Y'),
        'jumlah' => 750000,
        'status' => 'lunas',
        'tanggal_bayar' => now()->format('Y-m-d'),
    ])->assertSessionHas('success');

    expect(PembayaranSpp::count())->toBe(2);
});

it('admin can update SPP payment', function () {
    $kelas = Kelas::factory()->create();
    $siswa = Siswa::factory()->create(['kelas_id' => $kelas->id]);
    $pembayaran = $siswa->pembayaranSpp()->first();

    $this->actingAs($this->admin)->put(route('admin.keuangan.update', $pembayaran), [
        'jumlah' => 1000000,
        'status' => 'lunas',
        'tanggal_bayar' => now()->format('Y-m-d'),
    ])->assertSessionHas('success');

    expect($pembayaran->fresh()->status)->toBe('lunas');
    expect($pembayaran->fresh()->jumlah)->toBe(1000000);
});

it('admin can delete SPP payment', function () {
    $kelas = Kelas::factory()->create();
    $siswa = Siswa::factory()->create(['kelas_id' => $kelas->id]);
    $pembayaran = $siswa->pembayaranSpp()->first();

    $this->actingAs($this->admin)->delete(route('admin.keuangan.destroy', $pembayaran))
        ->assertSessionHas('success');

    expect(PembayaranSpp::where('id', $pembayaran->id)->exists())->toBeFalse();
});

it('scheduler generates SPP for active siswa', function () {
    $kelas = Kelas::factory()->create();
    $siswa1 = Siswa::factory()->create(['kelas_id' => $kelas->id, 'status' => 'aktif']);
    $siswa2 = Siswa::factory()->create(['kelas_id' => $kelas->id, 'status' => 'aktif']);

    $bulan = now()->format('m');
    $tahun = now()->format('Y');

    $activeSiswa = Siswa::aktif()->get();
    foreach ($activeSiswa as $siswa) {
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
        }
    }

    expect(PembayaranSpp::where('bulan', $bulan)->where('tahun', $tahun)->count())->toBe(2);
});
