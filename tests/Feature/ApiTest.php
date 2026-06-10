<?php

use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create(['role' => 'admin']);
});

it('returns siswa list from API', function () {
    Siswa::factory()->count(3)->create();

    $response = $this->actingAs($this->user)->getJson('/api/v1/siswa');

    $response->assertOk()
        ->assertJsonCount(3, 'data');
});

it('returns single siswa from API', function () {
    $siswa = Siswa::factory()->create();

    $response = $this->actingAs($this->user)->getJson("/api/v1/siswa/{$siswa->id}");

    $response->assertOk()
        ->assertJsonPath('nisn', $siswa->nisn);
});

it('returns jadwal list from API', function () {
    Kelas::factory()->count(2)->create();

    $response = $this->actingAs($this->user)->getJson('/api/v1/jadwal');

    $response->assertOk();
});

it('returns nilai list from API', function () {
    $response = $this->actingAs($this->user)->getJson('/api/v1/nilai');

    $response->assertOk();
});
