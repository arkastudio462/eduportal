<?php

use App\Models\Jurusan;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kelas', function (Blueprint $table) {
            $table->foreignId('jurusan_id')->nullable()->constrained('jurusan')->cascadeOnDelete();
        });

        $allJurusan = DB::table('kelas')->select('jurusan')->distinct()->whereNotNull('jurusan')->pluck('jurusan');

        foreach ($allJurusan as $nama) {
            $jurusan = Jurusan::firstOrCreate(['nama' => $nama], ['kode' => strtoupper(substr($nama, 0, 3))]);
            DB::table('kelas')->where('jurusan', $nama)->update(['jurusan_id' => $jurusan->id]);
        }

        Schema::table('kelas', function (Blueprint $table) {
            $table->dropColumn('jurusan');
        });
    }

    public function down(): void
    {
        Schema::table('kelas', function (Blueprint $table) {
            $table->string('jurusan')->nullable();
        });

        DB::table('kelas')->whereNotNull('jurusan_id')->get()->each(function ($kelas) {
            $jurusan = DB::table('jurusan')->find($kelas->jurusan_id);
            DB::table('kelas')->where('id', $kelas->id)->update(['jurusan' => $jurusan?->nama]);
        });

        Schema::table('kelas', function (Blueprint $table) {
            $table->dropConstrainedForeignId('jurusan_id');
        });
    }
};
