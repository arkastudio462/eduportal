<?php

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Siswa;
use App\Models\User;

beforeEach(function () {
    $this->admin = User::factory()->create(['role' => 'admin']);
});

it('admin can create siswa', function () {
    Kelas::factory()->create();

    $response = $this->actingAs($this->admin)->post(route('admin.siswa.store'), [
        'name' => 'Test Student',
        'email' => 'student@test.com',
        'password' => 'password123',
        'nisn' => '1234567890',
        'nis' => '2024001',
        'kelas_id' => Kelas::first()->id,
        'status' => 'aktif',
    ]);

    $response->assertRedirect(route('admin.data-siswa'));
    $response->assertSessionHas('success');

    expect(Siswa::count())->toBe(1);
    expect(User::where('email', 'student@test.com')->exists())->toBeTrue();
});

it('admin can update siswa', function () {
    $siswa = Siswa::factory()->create();

    $response = $this->actingAs($this->admin)->put(route('admin.siswa.update', $siswa), [
        'name' => 'Updated Name',
        'email' => $siswa->user->email,
        'nisn' => $siswa->nisn,
        'status' => 'nonaktif',
    ]);

    $response->assertRedirect(route('admin.data-siswa'));
    expect($siswa->fresh()->status)->toBe('nonaktif');
    expect($siswa->user->fresh()->name)->toBe('Updated Name');
});

it('admin can delete siswa', function () {
    $siswa = Siswa::factory()->create();
    $userId = $siswa->user_id;

    $this->actingAs($this->admin)->delete(route('admin.siswa.destroy', $siswa))
        ->assertRedirect(route('admin.data-siswa'));

    expect(Siswa::count())->toBe(0);
    expect(User::where('id', $userId)->exists())->toBeFalse();
});

it('admin can create guru', function () {
    Mapel::factory()->create();

    $response = $this->actingAs($this->admin)->post(route('admin.guru.store'), [
        'name' => 'Test Teacher',
        'email' => 'teacher@test.com',
        'password' => 'password123',
        'nuptk' => '9876543210',
        'nip' => '198001012024001',
        'mata_pelajaran' => Mapel::first()->nama,
    ]);

    $response->assertRedirect(route('admin.guru'));
    $response->assertSessionHas('success');

    expect(Guru::count())->toBe(1);
});

it('admin can update guru', function () {
    $guru = Guru::factory()->create();

    $response = $this->actingAs($this->admin)->put(route('admin.guru.update', $guru), [
        'name' => 'Updated Teacher',
        'email' => $guru->user->email,
        'nuptk' => $guru->nuptk,
        'mata_pelajaran' => $guru->mata_pelajaran,
    ]);

    $response->assertRedirect(route('admin.guru'));
    expect($guru->user->fresh()->name)->toBe('Updated Teacher');
});

it('admin can delete guru', function () {
    $guru = Guru::factory()->create();
    $userId = $guru->user_id;

    $this->actingAs($this->admin)->delete(route('admin.guru.destroy', $guru))
        ->assertRedirect(route('admin.guru'));

    expect(Guru::count())->toBe(0);
    expect(User::where('id', $userId)->exists())->toBeFalse();
});
