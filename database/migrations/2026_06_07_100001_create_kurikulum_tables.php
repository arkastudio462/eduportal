<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ki_kd', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mapel_id')->constrained('mapel')->cascadeOnDelete();
            $table->string('kode')->comment('Contoh: KI-1, KD-3.1');
            $table->enum('tipe', ['KI', 'KD']);
            $table->text('deskripsi');
            $table->string('semester')->nullable();
            $table->timestamps();
        });

        Schema::create('silabus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mapel_id')->constrained('mapel')->cascadeOnDelete();
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->string('file')->nullable();
            $table->string('semester')->nullable();
            $table->timestamps();
        });

        Schema::create('prota', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mapel_id')->constrained('mapel')->cascadeOnDelete();
            $table->string('tahun_ajaran');
            $table->text('deskripsi')->nullable();
            $table->string('file')->nullable();
            $table->timestamps();
        });

        Schema::create('promes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prota_id')->constrained('prota')->cascadeOnDelete();
            $table->string('bulan');
            $table->text('materi')->nullable();
            $table->integer('minggu_ke')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promes');
        Schema::dropIfExists('prota');
        Schema::dropIfExists('silabus');
        Schema::dropIfExists('ki_kd');
    }
};
