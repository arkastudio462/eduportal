<?php

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Nilai;
use App\Models\Siswa;
use App\Models\Ujian;
use App\Models\User;

beforeEach(function () {
    $this->admin = User::factory()->create(['role' => 'admin']);
});

it('can create siswa via API', function () {
    Kelas::factory()->create();

    $response = $this->actingAs($this->admin)->postJson('/api/v1/siswa', [
        'name' => 'API Student',
        'email' => 'api.student@test.com',
        'password' => 'password123',
        'nisn' => '9988776655',
        'nis' => '2024001',
        'kelas_id' => Kelas::first()->id,
        'status' => 'aktif',
    ]);

    $response->assertStatus(201);
    $response->assertJsonPath('nisn', '9988776655');
    expect(Siswa::where('nisn', '9988776655')->exists())->toBeTrue();
});

it('can update siswa via API', function () {
    $siswa = Siswa::factory()->create();

    $response = $this->actingAs($this->admin)->putJson('/api/v1/siswa/'.$siswa->id, [
        'status' => 'nonaktif',
    ]);

    $response->assertStatus(200);
    expect($siswa->fresh()->status)->toBe('nonaktif');
});

it('can delete siswa via API', function () {
    $siswa = Siswa::factory()->create();
    $userId = $siswa->user_id;

    $this->actingAs($this->admin)->deleteJson('/api/v1/siswa/'.$siswa->id)
        ->assertStatus(200);

    expect(Siswa::where('id', $siswa->id)->exists())->toBeFalse();
    expect(User::where('id', $userId)->exists())->toBeFalse();
});

it('blocks siswa from writing via API', function () {
    $siswaUser = User::factory()->create(['role' => 'siswa']);

    $this->actingAs($siswaUser)
        ->postJson('/api/v1/siswa', ['name' => 'X'])
        ->assertStatus(403);
});

it('can create nilai via API', function () {
    $siswa = Siswa::factory()->create();
    $ujian = Ujian::factory()->create();

    $response = $this->actingAs($this->admin)->postJson('/api/v1/nilai', [
        'siswa_id' => $siswa->id,
        'ujian_id' => $ujian->id,
        'skor' => 85,
    ]);

    $response->assertStatus(201);
    expect(Nilai::where('siswa_id', $siswa->id)->exists())->toBeTrue();
});

it('can delete nilai via API', function () {
    $siswa = Siswa::factory()->create();
    $ujian = Ujian::factory()->create();
    $nilai = Nilai::create([
        'siswa_id' => $siswa->id,
        'ujian_id' => $ujian->id,
        'skor' => 80,
    ]);

    $this->actingAs($this->admin)->deleteJson('/api/v1/nilai/'.$nilai->id)
        ->assertStatus(200);

    expect(Nilai::where('id', $nilai->id)->exists())->toBeFalse();
});
