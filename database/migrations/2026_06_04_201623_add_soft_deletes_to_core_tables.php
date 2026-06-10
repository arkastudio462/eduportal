<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('siswa', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('guru', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('kelas', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('mapel', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('siswa', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('guru', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('kelas', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('mapel', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
