<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ekskul_anggota', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ekskul_id')->constrained('ekskuls')->cascadeOnDelete();
            $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
            $table->string('status')->default('aktif');
            $table->text('keterangan')->nullable();
            $table->timestamps();
            $table->unique(['ekskul_id', 'siswa_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ekskul_anggota');
    }
};
