<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('siswa', function (Blueprint $table) {
            $table->string('nama_ayah')->nullable()->after('status');
            $table->string('nama_ibu')->nullable()->after('nama_ayah');
            $table->string('no_wa_ayah', 20)->nullable()->after('nama_ibu');
            $table->string('no_wa_ibu', 20)->nullable()->after('no_wa_ayah');
            $table->string('email_ayah')->nullable()->after('no_wa_ibu');
            $table->string('email_ibu')->nullable()->after('email_ayah');
        });
    }

    public function down(): void
    {
        Schema::table('siswa', function (Blueprint $table) {
            $table->dropColumn(['nama_ayah', 'nama_ibu', 'no_wa_ayah', 'no_wa_ibu', 'email_ayah', 'email_ibu']);
        });
    }
};
