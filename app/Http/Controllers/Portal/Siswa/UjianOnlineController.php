<?php

namespace App\Http\Controllers\Portal\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Nilai;
use App\Models\Siswa;
use App\Models\Ujian;
use Illuminate\Support\Facades\Auth;

class UjianOnlineController extends Controller
{
    public function index()
    {
        $siswa = Siswa::where('user_id', Auth::id())->first();
        $ujianList = collect();

        if ($siswa) {
            $ujianList = Ujian::with('mapel')
                ->whereHas('kelas', fn ($q) => $q->where('kelas.id', $siswa->kelas_id))
                ->orderBy('tanggal_mulai', 'desc')
                ->get();

            $nilaiSiswa = Nilai::where('siswa_id', $siswa->id)
                ->whereIn('ujian_id', $ujianList->pluck('id'))
                ->get()
                ->keyBy('ujian_id');

            $ujianList->each(function ($ujian) use ($nilaiSiswa) {
                $nilai = $nilaiSiswa->get($ujian->id);
                $ujian->sudah_dikerjakan = ! is_null($nilai);
                $ujian->nilai = $nilai;
            });
        }

        return view('portal-siswa.ujian-online', compact('ujianList'));
    }
}
