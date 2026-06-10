<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kepegawaian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guru_id')->constrained('guru')->cascadeOnDelete();
            $table->string('status_kepegawaian'); // PNS, PPPK, Non-PNS/GTT/PTT
            $table->string('golongan')->nullable(); // I/a, I/b, II/a, II/b, III/a, III/b, IV/a, IV/b, etc.
            $table->string('jabatan')->nullable();
            $table->date('tmt_cpns')->nullable();
            $table->date('tmt_pns')->nullable();
            $table->integer('masa_kerja_tahun')->nullable();
            $table->integer('masa_kerja_bulan')->nullable();
            $table->string('nik')->nullable();
            $table->string('npwp')->nullable();
            $table->string('karpeg')->nullable(); // nomor kartu pegawai
            $table->timestamps();
        });

        Schema::create('cuti_guru', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guru_id')->constrained('guru')->cascadeOnDelete();
            $table->string('jenis_cuti'); // tahunan, sakit, melahirkan, besar, alasan_penting, dinas_luar
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->text('alasan');
            $table->string('status')->default('pending'); // pending, disetujui, ditolak
            $table->string('file')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('kinerja_guru', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guru_id')->constrained('guru')->cascadeOnDelete();
            $table->string('tahun_ajaran');
            $table->string('semester');
            $table->integer('jam_mengajar_per_minggu')->nullable();
            $table->decimal('skor_pkg', 5, 2)->nullable();
            $table->string('predikat_pkg')->nullable(); // Amat Baik, Baik, Cukup, Kurang
            $table->string('kategori'); // guru_kelas, guru_mapel, guru_bk, lainnya
            $table->text('catatan')->nullable();
            $table->timestamps();
        });

        Schema::create('sertifikasi_guru', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guru_id')->constrained('guru')->cascadeOnDelete();
            $table->string('jenis_sertifikasi'); // pendidik, profesi, keahlian
            $table->string('nomor_sertifikat');
            $table->year('tahun_sertifikasi');
            $table->string('bidang_studi')->nullable();
            $table->string('penerbit');
            $table->string('file')->nullable();
            $table->timestamps();
        });

        Schema::create('tunjangan_guru', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guru_id')->constrained('guru')->cascadeOnDelete();
            $table->string('jenis_tunjangan'); // sertifikasi, fungsional, struktural, khusus, insentif
            $table->decimal('besaran', 12, 2);
            $table->string('periode_bulan');
            $table->year('periode_tahun');
            $table->string('status')->default('menunggu'); // dibayarkan, menunggu, ditangguhkan
            $table->date('tanggal_bayar')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tunjangan_guru');
        Schema::dropIfExists('sertifikasi_guru');
        Schema::dropIfExists('kinerja_guru');
        Schema::dropIfExists('cuti_guru');
        Schema::dropIfExists('kepegawaian');
    }
};
