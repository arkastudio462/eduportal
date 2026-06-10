<?php

use App\Models\Guru;
use App\Models\Siswa;
use App\Models\User;

it('redirects guests to login for admin routes', function () {
    $this->get(route('admin.dashboard'))
        ->assertRedirect(route('login'));
});

it('redirects guests to login for portal-siswa routes', function () {
    $this->get(route('portal-siswa.dashboard'))
        ->assertRedirect(route('login'));
});

it('redirects guests to login for portal-guru routes', function () {
    $this->get(route('portal-guru.dashboard'))
        ->assertRedirect(route('login'));
});

it('blocks siswa from admin routes', function () {
    $user = User::factory()->create(['role' => 'siswa']);

    $this->actingAs($user)
        ->get(route('admin.dashboard'))
        ->assertForbidden();
});

it('blocks guru from admin routes', function () {
    $user = User::factory()->create(['role' => 'guru']);

    $this->actingAs($user)
        ->get(route('admin.dashboard'))
        ->assertForbidden();
});

it('allows admin to access admin routes', function () {
    $user = User::factory()->create(['role' => 'admin']);

    $this->actingAs($user)
        ->get(route('admin.dashboard'))
        ->assertOk();
});

it('blocks admin from portal-siswa routes', function () {
    $user = User::factory()->create(['role' => 'admin']);

    $this->actingAs($user)
        ->get(route('portal-siswa.dashboard'))
        ->assertForbidden();
});

it('blocks guru from portal-siswa routes', function () {
    $user = User::factory()->create(['role' => 'guru']);

    $this->actingAs($user)
        ->get(route('portal-siswa.dashboard'))
        ->assertForbidden();
});

it('allows siswa to access portal-siswa routes', function () {
    $user = User::factory()->create(['role' => 'siswa']);
    Siswa::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user)
        ->get(route('portal-siswa.dashboard'))
        ->assertOk();
});

it('blocks admin from portal-guru routes', function () {
    $user = User::factory()->create(['role' => 'admin']);

    $this->actingAs($user)
        ->get(route('portal-guru.dashboard'))
        ->assertForbidden();
});

it('blocks siswa from portal-guru routes', function () {
    $user = User::factory()->create(['role' => 'siswa']);

    $this->actingAs($user)
        ->get(route('portal-guru.dashboard'))
        ->assertForbidden();
});

it('allows guru to access portal-guru routes', function () {
    $user = User::factory()->create(['role' => 'guru']);
    Guru::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user)
        ->get(route('portal-guru.dashboard'))
        ->assertOk();
});
