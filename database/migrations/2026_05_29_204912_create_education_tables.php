<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kelas', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('tingkat');
            $table->string('jurusan');
            $table->timestamps();
        });

        Schema::create('guru', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('nuptk')->unique();
            $table->string('nip')->nullable();
            $table->string('mata_pelajaran');
            $table->timestamps();
        });

        Schema::create('siswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('nisn')->unique();
            $table->string('nis')->nullable();
            $table->foreignId('kelas_id')->nullable()->constrained('kelas')->nullOnDelete();
            $table->string('status')->default('aktif');
            $table->timestamps();
        });

        Schema::create('mapel', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('kode')->nullable();
            $table->timestamps();
        });

        Schema::create('jadwal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelas_id')->constrained('kelas')->cascadeOnDelete();
            $table->foreignId('mapel_id')->constrained('mapel')->cascadeOnDelete();
            $table->foreignId('guru_id')->constrained('guru')->cascadeOnDelete();
            $table->string('hari');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->string('ruang')->nullable();
            $table->timestamps();
        });

        Schema::create('soal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mapel_id')->constrained('mapel')->cascadeOnDelete();
            $table->string('tipe');
            $table->string('kesulitan');
            $table->text('konten');
            $table->text('opsi')->nullable();
            $table->text('jawaban');
            $table->timestamps();
        });

        Schema::create('ujian', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->foreignId('mapel_id')->constrained('mapel')->cascadeOnDelete();
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->integer('durasi');
            $table->string('status');
            $table->timestamps();
        });

        Schema::create('ujian_kelas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ujian_id')->constrained('ujian')->cascadeOnDelete();
            $table->foreignId('kelas_id')->constrained('kelas')->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('nilai', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
            $table->foreignId('ujian_id')->constrained('ujian')->cascadeOnDelete();
            $table->decimal('skor', 5, 2);
            $table->integer('benar')->default(0);
            $table->integer('salah')->default(0);
            $table->integer('tidak_dijawab')->default(0);
            $table->timestamps();
        });

        Schema::create('pengumuman', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('konten');
            $table->dateTime('tanggal');
            $table->string('tipe')->default('umum');
            $table->timestamps();
        });

        Schema::create('pembayaran_spp', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
            $table->string('bulan');
            $table->string('tahun');
            $table->decimal('jumlah', 12, 2);
            $table->string('status');
            $table->date('tanggal_bayar')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayaran_spp');
        Schema::dropIfExists('pengumuman');
        Schema::dropIfExists('nilai');
        Schema::dropIfExists('ujian_kelas');
        Schema::dropIfExists('ujian');
        Schema::dropIfExists('soal');
        Schema::dropIfExists('jadwal');
        Schema::dropIfExists('mapel');
        Schema::dropIfExists('siswa');
        Schema::dropIfExists('guru');
        Schema::dropIfExists('kelas');
    }
};
