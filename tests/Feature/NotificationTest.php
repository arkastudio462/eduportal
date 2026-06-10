<?php

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Siswa;
use App\Models\User;
use App\Notifications\PengumumanNotification;
use App\Notifications\TugasBaruNotification;
use Illuminate\Support\Facades\Notification;

it('dispatches TugasBaruNotification when guru creates tugas', function () {
    Notification::fake();

    $mapel = Mapel::factory()->create();
    $kelas = Kelas::factory()->create();
    $guruUser = User::factory()->create(['role' => 'guru']);
    $guru = Guru::factory()->create([
        'user_id' => $guruUser->id,
        'mata_pelajaran' => $mapel->nama,
    ]);
    $siswa = Siswa::factory()->create(['kelas_id' => $kelas->id]);

    $this->actingAs($guruUser)->post(route('portal-guru.tugas.store'), [
        'judul' => 'PR',
        'deskripsi' => 'Kerjakan',
        'mapel_id' => $mapel->id,
        'kelas_id' => $kelas->id,
        'deadline' => now()->addDays(7),
    ]);

    Notification::assertSentTo($siswa->user, TugasBaruNotification::class);
});

it('dispatches PengumumanNotification when admin creates pengumuman', function () {
    Notification::fake();

    $admin = User::factory()->create(['role' => 'admin']);
    $siswa = User::factory()->create(['role' => 'siswa']);

    $this->actingAs($admin)->post(route('admin.pengumuman.store'), [
        'judul' => 'Libur Semester',
        'konten' => 'Semester genap akan berakhir',
        'tipe' => 'akademik',
    ]);

    Notification::assertSentTo($siswa, PengumumanNotification::class);
    Notification::assertSentTo($admin, PengumumanNotification::class);
});
