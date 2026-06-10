<?php

use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\User;

beforeEach(function () {
    $this->admin = User::factory()->create(['role' => 'admin']);
});

it('rejects siswa store with missing required fields', function () {
    $this->actingAs($this->admin)
        ->post(route('admin.siswa.store'), [])
        ->assertSessionHasErrors(['name', 'email', 'password', 'nisn', 'status']);
});

it('rejects siswa store with duplicate email', function () {
    User::factory()->create(['email' => 'dupe@test.com']);

    $this->actingAs($this->admin)
        ->post(route('admin.siswa.store'), [
            'name' => 'Test',
            'email' => 'dupe@test.com',
            'password' => 'password123',
            'nisn' => '1111111111',
            'status' => 'aktif',
        ])
        ->assertSessionHasErrors(['email']);
});

it('rejects siswa store with duplicate nisn', function () {
    Kelas::factory()->create();
    $this->actingAs($this->admin)->post(route('admin.siswa.store'), [
        'name' => 'First',
        'email' => 'first@test.com',
        'password' => 'password123',
        'nisn' => '1111111111',
        'status' => 'aktif',
    ])->assertSessionHasNoErrors();

    $this->actingAs($this->admin)->post(route('admin.siswa.store'), [
        'name' => 'Second',
        'email' => 'second@test.com',
        'password' => 'password123',
        'nisn' => '1111111111',
        'status' => 'aktif',
    ])->assertSessionHasErrors(['nisn']);
});

it('rejects guru store with invalid status value', function () {
    $this->actingAs($this->admin)
        ->post(route('admin.siswa.store'), [
            'name' => 'Test',
            'email' => 'test@test.com',
            'password' => 'password123',
            'nisn' => '1111111111',
            'status' => 'invalid_status',
        ])
        ->assertSessionHasErrors(['status']);
});

it('rejects pengumuman store with empty konten', function () {
    $this->actingAs($this->admin)
        ->post(route('admin.pengumuman.store'), [
            'judul' => 'Test',
            'konten' => '',
            'tipe' => 'umum',
        ])
        ->assertSessionHasErrors(['konten']);
});

it('rejects ujian store with past tanggal_selesai', function () {
    $mapel = Mapel::factory()->create();
    Kelas::factory()->create();

    $this->actingAs($this->admin)
        ->post(route('admin.ujian-online.store'), [
            'nama' => 'UTS',
            'mapel_id' => $mapel->id,
            'tanggal_mulai' => now()->addDays(5)->format('Y-m-d'),
            'tanggal_selesai' => now()->addDays(3)->format('Y-m-d'),
            'durasi' => 60,
            'status' => 'draft',
            'kelas_ids' => [Kelas::first()->id],
        ])
        ->assertSessionHasErrors(['tanggal_selesai']);
});
