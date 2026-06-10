<?php

use App\Models\User;

beforeEach(function () {
    $this->admin = User::factory()->create(['role' => 'admin']);
});

it('admin can access data-siswa page', function () {
    $this->actingAs($this->admin)
        ->get(route('admin.data-siswa'))
        ->assertOk();
});

it('admin can access data-guru page', function () {
    $this->actingAs($this->admin)
        ->get(route('admin.guru'))
        ->assertOk();
});

it('admin can access akademik page', function () {
    $this->actingAs($this->admin)
        ->get(route('admin.akademik'))
        ->assertOk();
});

it('admin can access nilai page', function () {
    $this->actingAs($this->admin)
        ->get(route('admin.nilai'))
        ->assertOk();
});

it('admin can access kelas page', function () {
    $this->actingAs($this->admin)
        ->get(route('admin.kelas'))
        ->assertOk();
});

it('admin can access keuangan page', function () {
    $this->actingAs($this->admin)
        ->get(route('admin.keuangan'))
        ->assertOk();
});

it('admin can access absensi page', function () {
    $this->actingAs($this->admin)
        ->get(route('admin.absensi'))
        ->assertOk();
});

it('admin can access pengumuman page', function () {
    $this->actingAs($this->admin)
        ->get(route('admin.pengumuman'))
        ->assertOk();
});

it('admin can access prestasi page', function () {
    $this->actingAs($this->admin)
        ->get(route('admin.prestasi'))
        ->assertOk();
});

it('admin can access pengaturan page', function () {
    $this->actingAs($this->admin)
        ->get(route('admin.pengaturan'))
        ->assertOk();
});

it('admin can access ujian-online page', function () {
    $this->actingAs($this->admin)
        ->get(route('admin.ujian-online'))
        ->assertOk();
});

it('admin can access bank-soal page', function () {
    $this->actingAs($this->admin)
        ->get(route('admin.bank-soal'))
        ->assertOk();
});
