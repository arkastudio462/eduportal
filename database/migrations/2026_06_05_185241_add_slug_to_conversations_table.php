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
        Schema::table('conversations', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('subjek');
        });

        DB::table('conversations')->orderBy('id')->each(function ($row) {
            $slug = Str::slug($row->subjek ?? 'percakapan-'.$row->id);
            $base = $slug;
            $counter = 1;
            while (DB::table('conversations')->where('slug', $slug)->exists()) {
                $slug = $base.'-'.$counter++;
            }
            DB::table('conversations')->where('id', $row->id)->update(['slug' => $slug]);
        });
    }

    public function down(): void
    {
        Schema::table('conversations', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
