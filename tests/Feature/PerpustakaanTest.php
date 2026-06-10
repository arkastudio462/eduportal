<?php

use App\Models\Buku;
use App\Models\Guru;
use App\Models\Siswa;
use App\Models\User;

beforeEach(function () {
    $this->guruUser = User::factory()->create(['role' => 'guru']);
    $this->guru = Guru::factory()->create(['user_id' => $this->guruUser->id]);
    $this->siswaUser = User::factory()->create(['role' => 'siswa']);
    $this->siswa = Siswa::factory()->create(['user_id' => $this->siswaUser->id]);
    $this->admin = User::factory()->create(['role' => 'admin']);
});

it('guru can access perpustakaan page', function () {
    $this->actingAs($this->guruUser)
        ->get(route('portal-guru.perpustakaan'))
        ->assertOk();
});

it('guru can create buku', function () {
    $response = $this->actingAs($this->guruUser)->post(route('portal-guru.perpustakaan.store'), [
        'judul' => 'Matematika Dasar',
        'penulis' => 'Prof. Ahmad',
        'penerbit' => 'Erlangga',
        'kategori' => 'Pelajaran',
        'tahun_terbit' => 2024,
        'isbn' => '978-602-1234-56-7',
        'deskripsi' => 'Buku matematika untuk SMA',
        'stok' => 10,
    ]);

    $response->assertRedirect(route('portal-guru.perpustakaan'));
    expect(Buku::count())->toBe(1);
});

it('guru can update buku', function () {
    $buku = Buku::create([
        'judul' => 'Fisika Dasar',
        'penulis' => 'Dr. Budi',
        'penerbit' => 'Gramedia',
        'kategori' => 'Pelajaran',
        'stok' => 5,
    ]);

    $this->actingAs($this->guruUser)->put(route('portal-guru.perpustakaan.update', $buku), [
        'judul' => 'Fisika Lanjutan',
        'penulis' => 'Dr. Budi',
        'penerbit' => 'Gramedia',
        'kategori' => 'Pelajaran',
        'stok' => 8,
    ])->assertRedirect(route('portal-guru.perpustakaan'));

    expect($buku->fresh()->judul)->toBe('Fisika Lanjutan');
    expect($buku->fresh()->stok)->toBe(8);
});

it('guru can delete buku', function () {
    $buku = Buku::create([
        'judul' => 'Kimia Dasar',
        'penulis' => 'Siti',
        'penerbit' => 'Pustaka',
        'kategori' => 'Pelajaran',
        'stok' => 3,
    ]);

    $this->actingAs($this->guruUser)->delete(route('portal-guru.perpustakaan.destroy', $buku))
        ->assertRedirect(route('portal-guru.perpustakaan'));

    expect(Buku::count())->toBe(0);
});

it('siswa can view perpustakaan page', function () {
    $this->actingAs($this->siswaUser)
        ->get(route('portal-siswa.perpustakaan'))
        ->assertOk();
});
