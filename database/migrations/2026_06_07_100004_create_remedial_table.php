<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('remedial', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
            $table->foreignId('mapel_id')->constrained('mapel')->cascadeOnDelete();
            $table->enum('jenis', ['remedial', 'pengayaan']);
            $table->decimal('nilai_awal', 5, 2)->nullable();
            $table->decimal('nilai_akhir', 5, 2)->nullable();
            $table->date('tanggal');
            $table->text('keterangan')->nullable();
            $table->string('semester');
            $table->timestamps();
        });

        Schema::table('nilai', function (Blueprint $table) {
            $table->string('deskripsi')->nullable()->after('skor');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('remedial');
        Schema::table('nilai', function (Blueprint $table) {
            $table->dropColumn('deskripsi');
        });
    }
};
