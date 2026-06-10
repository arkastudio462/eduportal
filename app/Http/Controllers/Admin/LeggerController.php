<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Nilai;
use App\Models\Semester;
use App\Models\Siswa;
use Illuminate\Http\Request;

class LeggerController extends Controller
{
    public function index(Request $request)
    {
        $kelasList = Kelas::orderBy('tingkat')->orderBy('nama')->get();
        $mapelList = Mapel::orderBy('nama')->get();
        $semesterList = Semester::orderBy('id')->get();

        $kelasId = $request->kelas_id;
        $mapelId = $request->mapel_id;
        $semesterId = $request->semester_id;

        $data = collect();

        if ($kelasId && $mapelId && $semesterId) {
            $semester = Semester::find($semesterId);
            $semesterLabel = $semester ? ($semester->tahun_ajaran.' '.$semester->semester) : '';

            $siswaList = Siswa::with('user')
                ->where('kelas_id', $kelasId)
                ->aktif()
                ->orderBy('id')
                ->get();

            foreach ($siswaList as $siswa) {
                $nilaiList = Nilai::where('siswa_id', $siswa->id)
                    ->where('mapel_id', $mapelId)
                    ->where('semester', $semesterLabel)
                    ->get();

                $uh = $nilaiList->where('jenis', 'uh');
                $tugas = $nilaiList->where('jenis', 'tugas');
                $uts = $nilaiList->where('jenis', 'uts')->first();
                $uas = $nilaiList->where('jenis', 'uas')->first();

                $rataUh = $uh->count() > 0 ? round($uh->avg('skor'), 2) : null;
                $rataTugas = $tugas->count() > 0 ? round($tugas->avg('skor'), 2) : null;

                $data->push([
                    'siswa' => $siswa,
                    'rata_uh' => $rataUh,
                    'rata_tugas' => $rataTugas,
                    'uts' => $uts?->skor,
                    'uas' => $uas?->skor,
                    'nilai_akhir' => $this->hitungNilaiAkhir($rataUh, $rataTugas, $uts?->skor, $uas?->skor),
                ]);
            }
        }

        return view('admin.legger', compact(
            'kelasList', 'mapelList', 'semesterList',
            'kelasId', 'mapelId', 'semesterId', 'data'
        ));
    }

    private function hitungNilaiAkhir(?float $uh, ?float $tugas, ?float $uts, ?float $uas): ?float
    {
        $bobotUh = $uh ? $uh * 0.3 : 0;
        $bobotTugas = $tugas ? $tugas * 0.2 : 0;
        $bobotUts = $uts ? $uts * 0.25 : 0;
        $bobotUas = $uas ? $uas * 0.25 : 0;

        $total = $bobotUh + $bobotTugas + $bobotUts + $bobotUas;
        $pembagi = ($uh ? 0.3 : 0) + ($tugas ? 0.2 : 0) + ($uts ? 0.25 : 0) + ($uas ? 0.25 : 0);

        return $pembagi > 0 ? round($total / $pembagi, 2) : null;
    }
}
