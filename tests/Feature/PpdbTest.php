<?php

use App\Models\BerkasPendaftaran;
use App\Models\Jurusan;
use App\Models\SiswaBerkas;
use App\Models\Kelas;
use App\Models\Pendaftaran;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    $this->admin = User::factory()->create(['role' => 'admin']);
});

it('can update pendaftaran status to diterima and create siswa', function () {
    $jurusan = Jurusan::factory()->create(['nama' => 'IPA']);
    $kelas = Kelas::factory()->create([
        'tingkat' => 'X',
        'jurusan_id' => $jurusan->id,
    ]);

    $pendaftaran = Pendaftaran::create([
        'no_pendaftaran' => 'PPDB-TEST-001',
        'nama_lengkap' => 'Test Student',
        'nisn' => '9999999990',
        'jenis_kelamin' => 'L',
        'tempat_lahir' => 'Jakarta',
        'tanggal_lahir' => '2010-01-01',
        'agama' => 'Islam',
        'alamat' => 'Jl. Test',
        'no_hp' => '08123456789',
        'email' => 'test@example.com',
        'asal_sekolah' => 'SMP Test',
        'jurusan_daftar' => 'IPA',
        'nilai_rata_rata' => '85',
        'nama_ayah' => 'Ayah Test',
        'nama_ibu' => 'Ibu Test',
        'no_hp_ayah' => '08123456780',
        'no_hp_ibu' => '08123456781',
        'pekerjaan_ayah' => 'PNS',
        'pekerjaan_ibu' => 'IRT',
        'status' => 'menunggu',
    ]);

    $this->actingAs($this->admin)
        ->put(route('admin.ppdb.update-status', $pendaftaran), [
            'status' => 'diterima',
            'catatan' => 'Selamat diterima',
        ])
        ->assertRedirect(route('admin.ppdb'))
        ->assertSessionHas('success');

    expect(Siswa::where('nisn', '9999999990')->exists())->toBeTrue();

    $siswa = Siswa::where('nisn', '9999999990')->first();
    expect($siswa->user->role->value)->toBe('siswa');
    expect($siswa->kelas_id)->toBe($kelas->id);
    expect($siswa->status)->toBe('aktif');

    expect(Pendaftaran::where('no_pendaftaran', 'PPDB-TEST-001')->exists())->toBeFalse();
});

it('moves berkas and sets profile photo when accepting pendaftaran', function () {
    Storage::fake('public');

    $jurusan = Jurusan::factory()->create(['nama' => 'IPA']);
    $kelas = Kelas::factory()->create([
        'tingkat' => 'X',
        'jurusan_id' => $jurusan->id,
    ]);

    $pendaftaran = Pendaftaran::create([
        'no_pendaftaran' => 'PPDB-TEST-010',
        'nama_lengkap' => 'Test Student Berkas',
        'nisn' => '9999999999',
        'jenis_kelamin' => 'L',
        'jurusan_daftar' => 'IPA',
        'asal_sekolah' => 'SMP Test',
        'status' => 'menunggu',
    ]);

    $foto = UploadedFile::fake()->create('foto.jpg', 50);
    $ijazah = UploadedFile::fake()->create('ijazah.pdf', 100);
    $fotoPath = $foto->store('berkas-pendaftaran', 'public');
    $ijazahPath = $ijazah->store('berkas-pendaftaran', 'public');

    BerkasPendaftaran::create([
        'pendaftaran_id' => $pendaftaran->id,
        'jenis' => 'foto',
        'file_path' => $fotoPath,
        'original_name' => 'foto.jpg',
    ]);
    BerkasPendaftaran::create([
        'pendaftaran_id' => $pendaftaran->id,
        'jenis' => 'ijazah',
        'file_path' => $ijazahPath,
        'original_name' => 'ijazah.pdf',
    ]);

    $this->actingAs($this->admin)
        ->put(route('admin.ppdb.update-status', $pendaftaran), [
            'status' => 'diterima',
        ])
        ->assertRedirect(route('admin.ppdb'))
        ->assertSessionHas('success');

    $siswa = Siswa::where('nisn', '9999999999')->first();

    expect($siswa->berkas->count())->toBe(2);
    expect($siswa->berkas->where('jenis', 'foto')->first())->not->toBeNull();
    expect($siswa->berkas->where('jenis', 'ijazah')->first())->not->toBeNull();

    foreach ($siswa->berkas as $berkas) {
        Storage::disk('public')->assertExists($berkas->file_path);
    }

    expect($siswa->user->profile_photo_path)->not->toBeNull();
    Storage::disk('public')->assertExists($siswa->user->profile_photo_path);

    expect(Pendaftaran::where('no_pendaftaran', 'PPDB-TEST-010')->exists())->toBeFalse();
});

it('does not create duplicate siswa when nisn already exists', function () {
    $jurusan = Jurusan::factory()->create(['nama' => 'IPA']);
    Kelas::factory()->create([
        'tingkat' => 'X',
        'jurusan_id' => $jurusan->id,
    ]);

    $existingSiswa = Siswa::factory()->create(['nisn' => '9999999991']);

    $pendaftaran = Pendaftaran::create([
        'no_pendaftaran' => 'PPDB-TEST-002',
        'nama_lengkap' => 'Test Student 2',
        'nisn' => '9999999991',
        'jenis_kelamin' => 'P',
        'tempat_lahir' => 'Jakarta',
        'tanggal_lahir' => '2010-02-02',
        'agama' => 'Islam',
        'jurusan_daftar' => 'IPA',
        'asal_sekolah' => 'SMP Test',
        'status' => 'menunggu',
    ]);

    $response = $this->actingAs($this->admin)
        ->put(route('admin.ppdb.update-status', $pendaftaran), [
            'status' => 'diterima',
        ]);

    $response->assertRedirect(route('admin.ppdb.verifikasi', $pendaftaran));
    $response->assertSessionHas('success');

    expect(Siswa::where('nisn', '9999999991')->count())->toBe(1);
    expect(Pendaftaran::where('no_pendaftaran', 'PPDB-TEST-002')->exists())->toBeTrue();
});

it('can update pendaftaran status to ditolak without creating siswa', function () {
    $pendaftaran = Pendaftaran::create([
        'no_pendaftaran' => 'PPDB-TEST-003',
        'nama_lengkap' => 'Test Student 3',
        'nisn' => '9999999992',
        'jurusan_daftar' => 'IPA',
        'asal_sekolah' => 'SMP Test',
        'status' => 'menunggu',
    ]);

    $response = $this->actingAs($this->admin)
        ->put(route('admin.ppdb.update-status', $pendaftaran), [
            'status' => 'ditolak',
            'catatan' => 'Maaf, tidak diterima',
        ]);

    $response->assertRedirect(route('admin.ppdb.verifikasi', $pendaftaran));
    $response->assertSessionHas('success');

    expect(Siswa::where('nisn', '9999999992')->exists())->toBeFalse();
    expect(Pendaftaran::where('no_pendaftaran', 'PPDB-TEST-003')->exists())->toBeTrue();
});
