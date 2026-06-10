<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Remedial;
use App\Models\Semester;
use App\Models\Siswa;
use Illuminate\Http\Request;

class RemedialController extends Controller
{
    public function index(Request $request)
    {
        $kelasList = Kelas::orderBy('tingkat')->orderBy('nama')->get();
        $mapelList = Mapel::orderBy('nama')->get();
        $semesterSemua = Semester::orderByDesc('id')->get();
        $semesterList = $semesterSemua->map(fn ($s) => $s->tahun_ajaran.' '.$s->semester);

        $query = Remedial::with('siswa.user', 'mapel');

        $filterKelasId = $request->kelas_id;
        $filterMapelId = $request->mapel_id;
        $filterJenis = $request->jenis;
        $filterSemester = $request->semester;

        if ($request->filled('kelas_id')) {
            $query->whereHas('siswa', fn ($q) => $q->where('kelas_id', $request->kelas_id));
        }

        if ($request->filled('mapel_id')) {
            $query->where('mapel_id', $request->mapel_id);
        }

        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        if ($request->filled('semester')) {
            $query->where('semester', $request->semester);
        }

        $semuaRemedial = $query->latest()->paginate(20);

        return view('admin.remedial', compact(
            'semuaRemedial', 'kelasList', 'mapelList', 'semesterList',
            'filterKelasId', 'filterMapelId', 'filterJenis', 'filterSemester'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'mapel_id' => 'required|exists:mapel,id',
            'jenis' => 'required|in:remedial,pengayaan',
            'nilai_awal' => 'nullable|numeric|min:0|max:100',
            'nilai_akhir' => 'nullable|numeric|min:0|max:100',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string',
            'semester' => 'required|string',
        ]);

        Remedial::create($validated);

        return redirect()->route('admin.remedial')->with('success', 'Data remedial/pengayaan berhasil ditambahkan.');
    }

    public function update(Request $request, Remedial $remedial)
    {
        $validated = $request->validate([
            'nilai_awal' => 'nullable|numeric|min:0|max:100',
            'nilai_akhir' => 'nullable|numeric|min:0|max:100',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        $remedial->update($validated);

        return redirect()->route('admin.remedial')->with('success', 'Data remedial/pengayaan berhasil diperbarui.');
    }

    public function destroy(Remedial $remedial)
    {
        $remedial->delete();

        return redirect()->route('admin.remedial')->with('success', 'Data remedial/pengayaan berhasil dihapus.');
    }

    public function getSiswaByKelas(Request $request)
    {
        $request->validate(['kelas_id' => 'required|exists:kelas,id']);

        $siswa = Siswa::with('user')
            ->where('kelas_id', $request->kelas_id)
            ->aktif()
            ->orderBy('id')
            ->get()
            ->map(fn ($s) => ['id' => $s->id, 'nama' => $s->user->name]);

        return response()->json($siswa);
    }
}
