<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('nilai', function (Blueprint $table) {
            $table->foreignId('ujian_id')->nullable()->change();
            $table->foreignId('mapel_id')->nullable()->after('ujian_id')->constrained('mapel')->nullOnDelete();
            $table->string('jenis')->default('ujian')->after('mapel_id');
            $table->string('semester')->nullable()->after('jenis');
        });
    }

    public function down(): void
    {
        Schema::table('nilai', function (Blueprint $table) {
            $table->dropColumn(['mapel_id', 'jenis', 'semester']);
            $table->foreignId('ujian_id')->nullable(false)->change();
        });
    }
};
