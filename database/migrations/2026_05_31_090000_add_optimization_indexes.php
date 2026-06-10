<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Foreign key indexes
        Schema::table('kelas', function (Blueprint $table) {
            $table->index('jurusan_id');
            $table->index('wali_kelas_id');
        });

        Schema::table('guru', function (Blueprint $table) {
            $table->index('user_id');
        });

        Schema::table('siswa', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('kelas_id');
            $table->index('status');
        });

        Schema::table('jadwal', function (Blueprint $table) {
            $table->index('kelas_id');
            $table->index('mapel_id');
            $table->index('guru_id');
            $table->index('hari');
        });

        Schema::table('soal', function (Blueprint $table) {
            $table->index('mapel_id');
            $table->index('tipe');
            $table->index('kesulitan');
        });

        Schema::table('ujian', function (Blueprint $table) {
            $table->index('mapel_id');
            $table->index('status');
        });

        Schema::table('ujian_kelas', function (Blueprint $table) {
            $table->index('ujian_id');
            $table->index('kelas_id');
        });

        Schema::table('nilai', function (Blueprint $table) {
            $table->index('siswa_id');
            $table->index('ujian_id');
            $table->index('mapel_id');
            $table->index('jenis');
            $table->index('semester');
        });

        Schema::table('pembayaran_spp', function (Blueprint $table) {
            $table->index('siswa_id');
            $table->index('bulan');
            $table->index('tahun');
            $table->index('status');
        });

        Schema::table('tugas', function (Blueprint $table) {
            $table->index('guru_id');
            $table->index('mapel_id');
            $table->index('kelas_id');
        });

        Schema::table('tugas_submissions', function (Blueprint $table) {
            $table->index('tugas_id');
            $table->index('siswa_id');
        });

        Schema::table('absensi', function (Blueprint $table) {
            $table->index('siswa_id');
            $table->index('jadwal_id');
            $table->index('status');
            $table->index('tanggal');
        });

        Schema::table('peminjaman', function (Blueprint $table) {
            $table->index('buku_id');
            $table->index('siswa_id');
            $table->index('guru_id');
            $table->index('status');
        });

        Schema::table('pengumuman', function (Blueprint $table) {
            $table->index('tipe');
        });

        Schema::table('kontak_messages', function (Blueprint $table) {
            $table->index('dibaca');
        });

        Schema::table('beritas', function (Blueprint $table) {
            $table->index('kategori');
            $table->index('is_utama');
        });

        Schema::table('prestasi', function (Blueprint $table) {
            $table->index('tingkat');
            $table->index('tipe');
        });

        Schema::table('tracer_study', function (Blueprint $table) {
            $table->index('tahun_lulus');
        });
    }

    public function down(): void
    {
        Schema::table('kelas', function (Blueprint $table) {
            $table->dropIndex(['jurusan_id']);
            $table->dropIndex(['wali_kelas_id']);
        });

        Schema::table('guru', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
        });

        Schema::table('siswa', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['kelas_id']);
            $table->dropIndex(['status']);
        });

        Schema::table('jadwal', function (Blueprint $table) {
            $table->dropIndex(['kelas_id']);
            $table->dropIndex(['mapel_id']);
            $table->dropIndex(['guru_id']);
            $table->dropIndex(['hari']);
        });

        Schema::table('soal', function (Blueprint $table) {
            $table->dropIndex(['mapel_id']);
            $table->dropIndex(['tipe']);
            $table->dropIndex(['kesulitan']);
        });

        Schema::table('ujian', function (Blueprint $table) {
            $table->dropIndex(['mapel_id']);
            $table->dropIndex(['status']);
        });

        Schema::table('ujian_kelas', function (Blueprint $table) {
            $table->dropIndex(['ujian_id']);
            $table->dropIndex(['kelas_id']);
        });

        Schema::table('nilai', function (Blueprint $table) {
            $table->dropIndex(['siswa_id']);
            $table->dropIndex(['ujian_id']);
            $table->dropIndex(['mapel_id']);
            $table->dropIndex(['jenis']);
            $table->dropIndex(['semester']);
        });

        Schema::table('pembayaran_spp', function (Blueprint $table) {
            $table->dropIndex(['siswa_id']);
            $table->dropIndex(['bulan']);
            $table->dropIndex(['tahun']);
            $table->dropIndex(['status']);
        });

        Schema::table('tugas', function (Blueprint $table) {
            $table->dropIndex(['guru_id']);
            $table->dropIndex(['mapel_id']);
            $table->dropIndex(['kelas_id']);
        });

        Schema::table('tugas_submissions', function (Blueprint $table) {
            $table->dropIndex(['tugas_id']);
            $table->dropIndex(['siswa_id']);
        });

        Schema::table('absensi', function (Blueprint $table) {
            $table->dropIndex(['siswa_id']);
            $table->dropIndex(['jadwal_id']);
            $table->dropIndex(['status']);
            $table->dropIndex(['tanggal']);
        });

        Schema::table('peminjaman', function (Blueprint $table) {
            $table->dropIndex(['buku_id']);
            $table->dropIndex(['siswa_id']);
            $table->dropIndex(['guru_id']);
            $table->dropIndex(['status']);
        });

        Schema::table('pengumuman', function (Blueprint $table) {
            $table->dropIndex(['tipe']);
        });

        Schema::table('kontak_messages', function (Blueprint $table) {
            $table->dropIndex(['dibaca']);
        });

        Schema::table('beritas', function (Blueprint $table) {
            $table->dropIndex(['kategori']);
            $table->dropIndex(['is_utama']);
        });

        Schema::table('prestasi', function (Blueprint $table) {
            $table->dropIndex(['tingkat']);
            $table->dropIndex(['tipe']);
        });

        Schema::table('tracer_study', function (Blueprint $table) {
            $table->dropIndex(['tahun_lulus']);
        });
    }
};
