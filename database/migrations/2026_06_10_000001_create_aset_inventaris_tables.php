<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ruang', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique();
            $table->string('nama');
            $table->string('lantai')->nullable();
            $table->string('gedung')->nullable();
            $table->integer('kapasitas')->nullable();
            $table->string('jenis')->default('kelas');
            $table->text('keterangan')->nullable();
            $table->string('status')->default('tersedia');
            $table->timestamps();
        });

        Schema::create('barang', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique();
            $table->string('nama');
            $table->string('kategori');
            $table->foreignId('ruang_id')->nullable()->constrained('ruang')->nullOnDelete();
            $table->integer('jumlah')->default(1);
            $table->string('kondisi')->default('baik');
            $table->string('merek')->nullable();
            $table->year('tahun_peroleh')->nullable();
            $table->string('sumber_dana')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        Schema::create('peminjaman_aset', function (Blueprint $table) {
            $table->id();
            $table->string('peminjam_type');
            $table->unsignedBigInteger('peminjam_id');
            $table->foreignId('ruang_id')->nullable()->constrained('ruang')->nullOnDelete();
            $table->foreignId('barang_id')->nullable()->constrained('barang')->nullOnDelete();
            $table->string('tujuan');
            $table->dateTime('tanggal_mulai');
            $table->dateTime('tanggal_selesai');
            $table->string('status')->default('diajukan');
            $table->text('keterangan')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('maintenance_aset', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barang_id')->nullable()->constrained('barang')->nullOnDelete();
            $table->foreignId('ruang_id')->nullable()->constrained('ruang')->nullOnDelete();
            $table->string('jenis')->default('perbaikan');
            $table->text('deskripsi');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai')->nullable();
            $table->decimal('biaya', 15, 2)->nullable();
            $table->string('status')->default('direncanakan');
            $table->string('pelaksana')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenance_aset');
        Schema::dropIfExists('peminjaman_aset');
        Schema::dropIfExists('barang');
        Schema::dropIfExists('ruang');
    }
};
