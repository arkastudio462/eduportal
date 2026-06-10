<?php

use App\Jobs\ExportDataJob;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Support\Facades\Queue;

beforeEach(function () {
    $this->admin = User::factory()->create(['role' => 'admin']);
});

it('admin can export siswa CSV', function () {
    $kelas = Kelas::factory()->create();
    Siswa::factory()->count(3)->create(['kelas_id' => $kelas->id]);

    $this->actingAs($this->admin)
        ->get(route('admin.export.siswa'))
        ->assertOk()
        ->assertHeader('Content-Type', 'text/csv; charset=utf-8');
});

it('admin can export nilai CSV', function () {
    $this->actingAs($this->admin)
        ->get(route('admin.export.nilai'))
        ->assertOk()
        ->assertHeader('Content-Type', 'text/csv; charset=utf-8');
});

it('admin can export absensi CSV', function () {
    $this->actingAs($this->admin)
        ->get(route('admin.export.absensi'))
        ->assertOk()
        ->assertHeader('Content-Type', 'text/csv; charset=utf-8');
});

it('ExportDataJob creates CSV for siswa', function () {
    Queue::fake();

    Kelas::factory()->create();
    Siswa::factory()->count(2)->create();

    $job = new ExportDataJob('siswa');
    $path = $job->handle();

    expect($path)->toMatch('/^exports\/siswa_/');
});

it('ExportDataJob creates CSV for nilai', function () {
    $job = new ExportDataJob('nilai');
    $filePath = $job->handle();

    expect($filePath)->toMatch('/^exports\/nilai_/');
});

it('ExportDataJob rejects unknown type', function () {
    (new ExportDataJob('invalid'))->handle();
})->throws(InvalidArgumentException::class);
