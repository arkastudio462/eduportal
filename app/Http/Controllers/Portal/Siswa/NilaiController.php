<?php

namespace App\Http\Controllers\Portal\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Nilai;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NilaiController extends Controller
{
    public function index(Request $request)
    {
        $siswa = Siswa::where('user_id', Auth::id())->first();
        if (! $siswa) {
            return view('portal-siswa.nilai', ['nilaiPerMapel' => collect(), 'semesterAktif' => null, 'semesterList' => collect()]);
        }

        $semesterList = Nilai::where('siswa_id', $siswa->id)
            ->select('semester')->distinct()->orderBy('semester')->pluck('semester');

        $semesterAktif = $request->input('semester', $semesterList->last());

        $dataNilai = Nilai::with('mapel', 'ujian')
            ->where('siswa_id', $siswa->id)
            ->where('semester', $semesterAktif)
            ->get()
            ->groupBy('mapel_id');

        $nilaiPerMapel = collect();
        foreach ($dataNilai as $mapelId => $items) {
            $mapel = $items->first()->mapel;
            if (! $mapel) {
                continue;
            }
            $nilaiPerMapel->push([
                'mapel' => $mapel,
                'items' => $items,
                'rata_rata' => round($items->avg('skor'), 1),
            ]);
        }

        return view('portal-siswa.nilai', compact('nilaiPerMapel', 'semesterAktif', 'semesterList'));
    }
}
