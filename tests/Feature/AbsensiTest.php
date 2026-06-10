<?php

use App\Models\Absensi;
use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Siswa;
use App\Models\User;

beforeEach(function () {
    $this->admin = User::factory()->create(['role' => 'admin']);
    $this->guruUser = User::factory()->create(['role' => 'guru']);
    $this->guru = Guru::factory()->create(['user_id' => $this->guruUser->id]);
    $this->mapel = Mapel::factory()->create();
    $this->kelas = Kelas::factory()->create();
    $this->siswa = Siswa::factory()->create(['kelas_id' => $this->kelas->id]);
    Jadwal::factory()->create([
        'guru_id' => $this->guru->id,
        'kelas_id' => $this->kelas->id,
        'mapel_id' => $this->mapel->id,
    ]);
});

it('admin can access absensi page', function () {
    $this->actingAs($this->admin)
        ->get(route('admin.absensi'))
        ->assertOk();
});

it('guru can access absensi page', function () {
    $this->actingAs($this->guruUser)
        ->get(route('portal-guru.absensi'))
        ->assertOk();
});

it('guru can store absensi records', function () {
    $this->actingAs($this->guruUser)->post(route('portal-guru.absensi.store'), [
        'kelas_id' => $this->kelas->id,
        'tanggal' => now()->format('Y-m-d'),
        'absensi' => [
            $this->siswa->id => ['status' => 'hadir', 'keterangan' => ''],
        ],
    ])->assertSessionHas('success');

    expect(Absensi::count())->toBe(1);
    expect(Absensi::first()->status)->toBe('hadir');
});

it('admin can store absensi record', function () {
    $this->actingAs($this->admin)->post(route('admin.absensi.store'), [
        'siswa_id' => $this->siswa->id,
        'tanggal' => now()->format('Y-m-d'),
        'status' => 'sakit',
        'keterangan' => 'Demam',
    ])->assertSessionHas('success');

    expect(Absensi::count())->toBe(1);
});

it('admin can update absensi record', function () {
    $absensi = Absensi::create([
        'siswa_id' => $this->siswa->id,
        'tanggal' => now()->format('Y-m-d'),
        'status' => 'hadir',
    ]);

    $this->actingAs($this->admin)->put(route('admin.absensi.update', $absensi), [
        'status' => 'alpha',
        'keterangan' => 'Tanpa keterangan',
    ])->assertSessionHas('success');

    expect($absensi->fresh()->status)->toBe('alpha');
});

it('admin can delete absensi record', function () {
    $absensi = Absensi::create([
        'siswa_id' => $this->siswa->id,
        'tanggal' => now()->format('Y-m-d'),
        'status' => 'izin',
    ]);

    $this->actingAs($this->admin)->delete(route('admin.absensi.destroy', $absensi))
        ->assertSessionHas('success');

    expect(Absensi::count())->toBe(0);
});
