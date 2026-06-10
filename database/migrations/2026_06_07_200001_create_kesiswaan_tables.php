<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Izin Siswa Online
        Schema::create('izin_siswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->string('alasan');
            $table->text('keterangan')->nullable();
            $table->string('file')->nullable();
            $table->enum('status', ['pending', 'disetujui', 'ditolak'])->default('pending');
            $table->foreignId('disetujui_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // 2. Pelanggaran Siswa
        Schema::create('kategori_pelanggaran', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->integer('poin')->default(0);
            $table->text('sanksi')->nullable();
            $table->timestamps();
        });

        Schema::create('pelanggaran_siswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
            $table->foreignId('kategori_id')->nullable()->constrained('kategori_pelanggaran')->nullOnDelete();
            $table->date('tanggal');
            $table->string('pelanggaran');
            $table->integer('poin')->default(0);
            $table->text('sanksi')->nullable();
            $table->text('keterangan')->nullable();
            $table->foreignId('dicatat_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // 3. Mutasi Siswa
        Schema::create('mutasi_siswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
            $table->enum('jenis', ['masuk', 'keluar', 'pindah']);
            $table->date('tanggal');
            $table->string('sekolah_asal')->nullable();
            $table->string('sekolah_tujuan')->nullable();
            $table->text('alasan')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        // 4. Beasiswa
        Schema::create('beasiswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
            $table->string('nama_beasiswa');
            $table->string('penyelenggara');
            $table->year('tahun');
            $table->text('keterangan')->nullable();
            $table->string('file')->nullable();
            $table->timestamps();
        });

        // Add siswa_id to prestasi
        Schema::table('prestasi', function (Blueprint $table) {
            $table->foreignId('siswa_id')->nullable()->after('id')->constrained('siswa')->nullOnDelete();
        });

        // 5. Bimbingan Konseling
        Schema::create('konseling', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
            $table->foreignId('guru_id')->constrained('guru')->cascadeOnDelete();
            $table->date('tanggal');
            $table->time('jam')->nullable();
            $table->string('topik');
            $table->text('catatan')->nullable();
            $table->text('tindak_lanjut')->nullable();
            $table->enum('status', ['dijadwalkan', 'selesai', 'dibatalkan'])->default('dijadwalkan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('konseling');
        Schema::table('prestasi', function (Blueprint $table) {
            $table->dropForeign(['siswa_id']);
            $table->dropColumn('siswa_id');
        });
        Schema::dropIfExists('beasiswa');
        Schema::dropIfExists('mutasi_siswa');
        Schema::dropIfExists('pelanggaran_siswa');
        Schema::dropIfExists('kategori_pelanggaran');
        Schema::dropIfExists('izin_siswa');
    }
};
