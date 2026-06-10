<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ujian', function (Blueprint $table) {
            $table->string('slug')->unique()->nullable()->after('nama');
        });

        DB::table('ujian')->orderBy('id')->each(function ($row) {
            $slug = Str::slug($row->nama);
            $base = $slug;
            $counter = 1;
            while (DB::table('ujian')->where('slug', $slug)->exists()) {
                $slug = $base.'-'.$counter++;
            }
            DB::table('ujian')->where('id', $row->id)->update(['slug' => $slug]);
        });
    }

    public function down(): void
    {
        Schema::table('ujian', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
