<?php

namespace App\Http\Controllers\Portal\Guru;

use App\Http\Controllers\Controller;
use App\Http\Requests\Guru\StoreNilaiRequest;
use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Nilai;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NilaiController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $guru = Guru::where('user_id', $user->id)->first();

        if (! $guru) {
            return view('portal-guru.nilai', [
                'kelasList' => collect(),
                'mapelList' => collect(),
                'siswaList' => collect(),
                'selectedKelas' => null,
                'selectedMapel' => null,
                'dataNilai' => collect(),
            ]);
        }

        // All classes this teacher teaches (from jadwal)
        $kelasIds = Jadwal::where('guru_id', $guru->id)
            ->pluck('kelas_id')
            ->unique();
        $kelasList = Kelas::whereIn('id', $kelasIds)->orderBy('tingkat')->orderBy('nama')->get();

        // All subjects matching this teacher's specialization
        $mapelList = Mapel::where('nama', $guru->mata_pelajaran)->get();
        if ($mapelList->isEmpty()) {
            $mapelList = Mapel::orderBy('nama')->get();
        }

        $selectedKelas = $request->kelas_id ? Kelas::find($request->kelas_id) : $kelasList->first();
        $selectedMapel = $request->mapel_id ? Mapel::find($request->mapel_id) : $mapelList->first();

        $siswaList = collect();
        $dataNilai = collect();
        $semester = $request->semester ?? now()->month >= 7 ? 'Ganjil' : 'Genap';
        $tahunAjar = now()->year;

        if ($selectedKelas && $selectedMapel) {
            $siswaList = Siswa::with('user')
                ->where('kelas_id', $selectedKelas->id)
                ->aktif()
                ->orderBy(User::select('name')->whereColumn('users.id', 'siswa.user_id'))
                ->get();

            $dataNilai = Nilai::where('mapel_id', $selectedMapel->id)
                ->where('semester', $semester)
                ->where('jenis', $request->jenis ?? 'uh')
                ->get()
                ->keyBy('siswa_id');
        }

        return view('portal-guru.nilai', compact(
            'guru', 'kelasList', 'mapelList', 'siswaList', 'dataNilai',
            'selectedKelas', 'selectedMapel', 'semester', 'tahunAjar'
        ));
    }

    public function store(StoreNilaiRequest $request)
    {
        $validated = $request->validated();

        $user = Auth::user();
        $guru = Guru::where('user_id', $user->id)->first();

        foreach ($validated['nilai'] as $siswaId => $skor) {
            if ($skor === null || $skor === '') {
                continue;
            }

            Nilai::updateOrCreate(
                [
                    'siswa_id' => $siswaId,
                    'mapel_id' => $validated['mapel_id'],
                    'jenis' => $validated['jenis'],
                    'semester' => $validated['semester'],
                ],
                [
                    'skor' => $skor,
                    'benar' => 0,
                    'salah' => 0,
                    'tidak_dijawab' => 0,
                ]
            );
        }

        return redirect()->route('portal-guru.nilai', [
            'kelas_id' => $validated['kelas_id'],
            'mapel_id' => $validated['mapel_id'],
            'jenis' => $validated['jenis'],
            'semester' => $validated['semester'],
        ])->with('success', 'Nilai berhasil disimpan.');
    }
}
