<?php

use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Soal;
use App\Models\Tugas;
use App\Models\Ujian;
use App\Models\User;

beforeEach(function () {
    $this->mapel = Mapel::factory()->create(['nama' => 'Matematika']);

    $this->guruUser = User::factory()->create(['role' => 'guru']);
    $this->guru = Guru::factory()->create([
        'user_id' => $this->guruUser->id,
        'mata_pelajaran' => 'Matematika',
    ]);

    $this->otherGuruUser = User::factory()->create(['role' => 'guru']);
    $this->otherGuru = Guru::factory()->create([
        'user_id' => $this->otherGuruUser->id,
        'mata_pelajaran' => 'Fisika',
    ]);

    $this->kelas = Kelas::factory()->create();

    Jadwal::factory()->create([
        'guru_id' => $this->guru->id,
        'kelas_id' => $this->kelas->id,
        'mapel_id' => $this->mapel->id,
    ]);
});

it('guru can create tugas', function () {
    $response = $this->actingAs($this->guruUser)->post(route('portal-guru.tugas.store'), [
        'judul' => 'PR Matematika',
        'deskripsi' => 'Kerjakan soal di buku paket',
        'mapel_id' => $this->mapel->id,
        'kelas_id' => $this->kelas->id,
        'deadline' => now()->addDays(7),
    ]);

    $response->assertRedirect(route('portal-guru.tugas'));
    expect(Tugas::count())->toBe(1);
});

it('guru can delete own tugas', function () {
    $tugas = Tugas::create([
        'guru_id' => $this->guru->id,
        'mapel_id' => $this->mapel->id,
        'kelas_id' => $this->kelas->id,
        'judul' => 'PR',
    ]);

    $this->actingAs($this->guruUser)->delete(route('portal-guru.tugas.destroy', $tugas))
        ->assertRedirect(route('portal-guru.tugas'));

    expect(Tugas::count())->toBe(0);
});

it('guru cannot delete another guru tugas', function () {
    $tugas = Tugas::create([
        'guru_id' => $this->otherGuru->id,
        'mapel_id' => Mapel::factory()->create(['nama' => 'Fisika'])->id,
        'kelas_id' => $this->kelas->id,
        'judul' => 'PR Fisika',
    ]);

    $this->actingAs($this->guruUser)->delete(route('portal-guru.tugas.destroy', $tugas))
        ->assertForbidden();
});

it('guru can create soal', function () {
    $response = $this->actingAs($this->guruUser)->post(route('portal-guru.bank-soal.store'), [
        'tipe' => 'PG',
        'kesulitan' => 'Mudah',
        'konten' => 'Berapakah 2+2?',
        'jawaban' => '4',
    ]);

    $response->assertRedirect(route('portal-guru.bank-soal'));
    expect(Soal::count())->toBe(1);
});

it('guru can update own soal', function () {
    $soal = Soal::create([
        'mapel_id' => $this->mapel->id,
        'tipe' => 'PG',
        'kesulitan' => 'Mudah',
        'konten' => 'Berapakah 2+2?',
        'jawaban' => '4',
    ]);

    $this->actingAs($this->guruUser)->put(route('portal-guru.bank-soal.update', $soal), [
        'tipe' => 'PG',
        'kesulitan' => 'Sedang',
        'konten' => 'Berapakah 5+3?',
        'jawaban' => '8',
    ])->assertRedirect(route('portal-guru.bank-soal'));

    expect($soal->fresh()->kesulitan)->toBe('Sedang');
});

it('guru cannot update another guru soal', function () {
    $otherMapel = Mapel::factory()->create(['nama' => 'Fisika']);
    $soal = Soal::create([
        'mapel_id' => $otherMapel->id,
        'tipe' => 'PG',
        'kesulitan' => 'Mudah',
        'konten' => 'Hukum Newton?',
        'jawaban' => 'F=ma',
    ]);

    $this->actingAs($this->guruUser)->put(route('portal-guru.bank-soal.update', $soal), [
        'tipe' => 'PG',
        'kesulitan' => 'Sulit',
        'konten' => 'Hukum Newton?',
        'jawaban' => 'F=ma',
    ])->assertForbidden();
});

it('guru can create ujian', function () {
    Ujian::factory()->create([
        'mapel_id' => $this->mapel->id,
        'nama' => 'UTS Matematika',
        'status' => 'draft',
    ]);

    $response = $this->actingAs($this->guruUser)->post(route('portal-guru.ujian.store'), [
        'nama' => 'UTS Matematika',
        'mapel_id' => $this->mapel->id,
        'tanggal_mulai' => now()->addDays(7)->format('Y-m-d'),
        'tanggal_selesai' => now()->addDays(8)->format('Y-m-d'),
        'durasi' => 120,
        'status' => 'draft',
        'kelas_ids' => [$this->kelas->id],
    ]);

    $response->assertRedirect(route('portal-guru.ujian-online'));
    expect(Ujian::count())->toBeGreaterThanOrEqual(1);
});

it('guru cannot update another guru ujian', function () {
    $otherMapel = Mapel::factory()->create(['nama' => 'Fisika']);
    $ujian = Ujian::create([
        'nama' => 'UTS Fisika',
        'slug' => 'uts-fisika',
        'mapel_id' => $otherMapel->id,
        'tanggal_mulai' => now()->addDays(7),
        'tanggal_selesai' => now()->addDays(8),
        'durasi' => 120,
        'status' => 'draft',
    ]);

    $this->actingAs($this->guruUser)->put(route('portal-guru.ujian.update', $ujian), [
        'nama' => 'UAS Fisika',
        'mapel_id' => $otherMapel->id,
        'tanggal_mulai' => now()->addDays(14)->format('Y-m-d'),
        'tanggal_selesai' => now()->addDays(15)->format('Y-m-d'),
        'durasi' => 90,
        'status' => 'draft',
        'kelas_ids' => [$this->kelas->id],
    ])->assertForbidden();
});
