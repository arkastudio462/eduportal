<?php

use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Soal;
use App\Models\Tugas;
use App\Models\Ujian;
use App\Models\User;
use App\Policies\SoalPolicy;
use App\Policies\TugasPolicy;
use App\Policies\UjianPolicy;

beforeEach(function () {
    $this->mapelMatematika = Mapel::factory()->create(['nama' => 'Matematika']);
    $this->mapelFisika = Mapel::factory()->create(['nama' => 'Fisika']);

    $this->guruMatematikaUser = User::factory()->create(['role' => 'guru']);
    $this->guruMatematika = Guru::factory()->create([
        'user_id' => $this->guruMatematikaUser->id,
        'mata_pelajaran' => 'Matematika',
    ]);

    $this->guruFisikaUser = User::factory()->create(['role' => 'guru']);
    $this->guruFisika = Guru::factory()->create([
        'user_id' => $this->guruFisikaUser->id,
        'mata_pelajaran' => 'Fisika',
    ]);

    $this->admin = User::factory()->create(['role' => 'admin']);
    $this->siswaUser = User::factory()->create(['role' => 'siswa']);

    $this->kelas = Kelas::factory()->create();
    Jadwal::factory()->create([
        'guru_id' => $this->guruMatematika->id,
        'kelas_id' => $this->kelas->id,
        'mapel_id' => $this->mapelMatematika->id,
    ]);
});

// TugasPolicy
it('tugas policy allows guru to view any tugas', function () {
    expect((new TugasPolicy)->viewAny($this->guruMatematikaUser))->toBeTrue();
});

it('tugas policy allows admin to view any tugas', function () {
    expect((new TugasPolicy)->viewAny($this->admin))->toBeTrue();
});

it('tugas policy blocks siswa from viewing tugas', function () {
    expect((new TugasPolicy)->viewAny($this->siswaUser))->toBeFalse();
});

it('tugas policy allows guru to create tugas', function () {
    expect((new TugasPolicy)->create($this->guruMatematikaUser))->toBeTrue();
});

it('tugas policy blocks admin from creating tugas', function () {
    expect((new TugasPolicy)->create($this->admin))->toBeFalse();
});

it('tugas policy allows guru to delete own tugas', function () {
    $tugas = Tugas::create([
        'guru_id' => $this->guruMatematika->id,
        'mapel_id' => $this->mapelMatematika->id,
        'kelas_id' => $this->kelas->id,
        'judul' => 'PR',
    ]);

    expect((new TugasPolicy)->delete($this->guruMatematikaUser, $tugas))->toBeTrue();
});

it('tugas policy blocks guru from deleting another guru tugas', function () {
    $tugas = Tugas::create([
        'guru_id' => $this->guruFisika->id,
        'mapel_id' => $this->mapelFisika->id,
        'kelas_id' => $this->kelas->id,
        'judul' => 'PR Fisika',
    ]);

    expect((new TugasPolicy)->delete($this->guruMatematikaUser, $tugas))->toBeFalse();
});

// SoalPolicy
it('soal policy allows guru to create soal', function () {
    expect((new SoalPolicy)->create($this->guruMatematikaUser))->toBeTrue();
});

it('soal policy allows admin to create soal', function () {
    expect((new SoalPolicy)->create($this->admin))->toBeTrue();
});

it('soal policy allows guru to update own mapel soal', function () {
    $soal = Soal::create([
        'mapel_id' => $this->mapelMatematika->id,
        'tipe' => 'PG',
        'kesulitan' => 'Mudah',
        'konten' => '2+2=?',
        'jawaban' => '4',
    ]);

    expect((new SoalPolicy)->update($this->guruMatematikaUser, $soal))->toBeTrue();
});

it('soal policy blocks guru from updating another mapel soal', function () {
    $soal = Soal::create([
        'mapel_id' => $this->mapelFisika->id,
        'tipe' => 'PG',
        'kesulitan' => 'Mudah',
        'konten' => 'Hukum Newton?',
        'jawaban' => 'F=ma',
    ]);

    expect((new SoalPolicy)->update($this->guruMatematikaUser, $soal))->toBeFalse();
});

// UjianPolicy
it('ujian policy allows guru to create ujian', function () {
    expect((new UjianPolicy)->create($this->guruMatematikaUser))->toBeTrue();
});

it('ujian policy allows admin to create ujian', function () {
    expect((new UjianPolicy)->create($this->admin))->toBeTrue();
});

it('ujian policy allows guru to update own mapel ujian', function () {
    $ujian = Ujian::factory()->create([
        'mapel_id' => $this->mapelMatematika->id,
    ]);

    expect((new UjianPolicy)->update($this->guruMatematikaUser, $ujian))->toBeTrue();
});

it('ujian policy blocks guru from updating another mapel ujian', function () {
    $ujian = Ujian::factory()->create([
        'mapel_id' => $this->mapelFisika->id,
    ]);

    expect((new UjianPolicy)->update($this->guruMatematikaUser, $ujian))->toBeFalse();
});
