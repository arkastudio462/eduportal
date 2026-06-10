<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Nilai;
use App\Models\Semester;
use App\Models\Siswa;
use Illuminate\Http\Request;

class RaporController extends Controller
{
    public function index(Request $request)
    {
        $kelasList = Kelas::orderBy('tingkat')->orderBy('nama')->get();
        $semesterList = Semester::orderBy('id')->get();

        if ($semesterList->isEmpty()) {
            $semesterList = Nilai::select('semester')
                ->distinct()
                ->whereNotNull('semester')
                ->where('semester', '!=', '')
                ->orderBy('semester')
                ->get()
                ->map(fn($item) => (object) [
                    'id' => $item->semester,
                    'nama' => $item->semester,
                    'semester' => $item->semester,
                ]);
        }

        $kelasId = $request->kelas_id;
        $semesterId = $request->semester_id;

        if ($kelasId && !$semesterId && $semesterList->isNotEmpty()) {
            $semesterId = $semesterList->first()->id;
        }

        $dataSiswa = collect();

        if ($kelasId && $semesterId) {
            $semester = Semester::find($semesterId);
            $semesterLabel = $semester
                ? ($semester->tahun_ajaran.' '.$semester->semester)
                : ($semesterList->firstWhere('id', $semesterId)?->nama ?? '');

            $siswaList = Siswa::with('user', 'kelas')->where('kelas_id', $kelasId)->aktif()->orderBy('id')->get();

            foreach ($siswaList as $siswa) {
                $nilaiPerMapel = Nilai::with('mapel')->where('siswa_id', $siswa->id)
                    ->where('semester', $semesterLabel)
                    ->get()
                    ->groupBy('mapel_id');

                $mapelData = [];
                $totalNilai = 0;
                $countMapel = 0;

                foreach ($nilaiPerMapel as $mapelId => $items) {
                    $uh = $items->where('jenis', 'uh')->avg('skor');
                    $tugas = $items->where('jenis', 'tugas')->avg('skor');
                    $uts = $items->where('jenis', 'uts')->first()?->skor;
                    $uas = $items->where('jenis', 'uas')->first()?->skor;

                    $na = $this->hitungNilaiAkhir($uh, $tugas, $uts, $uas);
                    $mapelData[] = [
                        'mapel' => $items->first()->mapel->nama ?? '-',
                        'uh' => $uh ? round($uh, 2) : null,
                        'tugas' => $tugas ? round($tugas, 2) : null,
                        'uts' => $uts,
                        'uas' => $uas,
                        'na' => $na,
                        'huruf' => $this->konversiKeHuruf($na),
                        'deskripsi' => $items->first()?->deskripsi ?? $this->generateDeskripsi($na, $items->first()->mapel->nama ?? ''),
                    ];

                    if ($na) {
                        $totalNilai += $na;
                        $countMapel++;
                    }
                }

                $rataRata = $countMapel > 0 ? round($totalNilai / $countMapel, 2) : null;

                $dataSiswa->push([
                    'siswa' => $siswa,
                    'mapel' => $mapelData,
                    'rata_rata' => $rataRata,
                    'rata_huruf' => $this->konversiKeHuruf($rataRata),
                ]);
            }
        }

        return view('admin.rapor', compact('kelasList', 'semesterList', 'kelasId', 'semesterId', 'dataSiswa'));
    }

    public function show(Siswa $siswa, Request $request)
    {
        $semesterId = $request->semester_id;
        $semester = Semester::findOrFail($semesterId);
        $semesterLabel = $semester->tahun_ajaran.' '.$semester->semester;

        $siswa->load('user', 'kelas.waliKelas.user');

        $nilaiPerMapel = Nilai::with('mapel')->where('siswa_id', $siswa->id)
            ->where('semester', $semesterLabel)
            ->get()
            ->groupBy('mapel_id');

        $mapelData = [];
        foreach ($nilaiPerMapel as $mapelId => $items) {
            $uh = $items->where('jenis', 'uh')->avg('skor');
            $tugas = $items->where('jenis', 'tugas')->avg('skor');
            $uts = $items->where('jenis', 'uts')->first()?->skor;
            $uas = $items->where('jenis', 'uas')->first()?->skor;

            $na = $this->hitungNilaiAkhir($uh, $tugas, $uts, $uas);
            $mapel = $items->first()->mapel;
            $mapelData[] = [
                'mapel' => $mapel->nama ?? '-',
                'uh' => $uh ? round($uh, 2) : null,
                'tugas' => $tugas ? round($tugas, 2) : null,
                'uts' => $uts,
                'uas' => $uas,
                'na' => $na,
                'huruf' => $this->konversiKeHuruf($na),
                'deskripsi' => $items->first()?->deskripsi ?? $this->generateDeskripsi($na, $mapel->nama ?? ''),
            ];
        }

        return response()->json([
            'siswa' => ['nama' => $siswa->user->name, 'nisn' => $siswa->nisn, 'nis' => $siswa->nis],
            'kelas' => $siswa->kelas?->nama ?? '-',
            'wali_kelas' => $siswa->kelas?->waliKelas?->user?->name ?? '-',
            'semester' => $semesterLabel,
            'mapel' => $mapelData,
        ]);
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

    private function konversiKeHuruf(?float $nilai): string
    {
        if ($nilai === null) {
            return '-';
        }

        return match (true) {
            $nilai >= 92 => 'A',
            $nilai >= 83 => 'B',
            $nilai >= 75 => 'C',
            $nilai >= 60 => 'D',
            default => 'E',
        };
    }

    private function generateDeskripsi(?float $nilai, string $mapel): string
    {
        if ($nilai === null) {
            return 'Belum ada nilai';
        }

        return match (true) {
            $nilai >= 92 => "Sangat baik dalam menguasai materi $mapel secara keseluruhan.",
            $nilai >= 83 => "Baik dalam menguasai materi $mapel, perlu sedikit peningkatan.",
            $nilai >= 75 => "Cukup baik dalam menguasai materi $mapel, perlu lebih giat belajar.",
            $nilai >= 60 => "Kurang dalam menguasai materi $mapel, perlu bimbingan intensif.",
            default => "Sangat kurang dalam menguasai materi $mapel, perlu remedial.",
        };
    }
}
