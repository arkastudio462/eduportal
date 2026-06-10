<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Nilai;
use App\Models\Semester;
use App\Models\Siswa;
use App\Models\Ujian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class NilaiController extends Controller
{
    public function show(Nilai $nilai)
    {
        $nilai->load(['siswa.user', 'mapel', 'ujian']);

        return response()->json($nilai);
    }

    public function index(Request $request)
    {
        $kelasList = Cache::remember('daftar_kelas', 86400, fn () => Kelas::orderBy('tingkat')->orderBy('nama')->get());
        $mapelList = Cache::remember('daftar_mapel', 86400, fn () => Mapel::select('id', 'nama')->orderBy('nama')->get());
        $semesterList = Semester::orderByDesc('id')->get()->map(fn ($s) => $s->tahun_ajaran.' '.$s->semester);

        $kelasId = $request->input('kelas_id');
        $mapelId = $request->input('mapel_id');
        $jenis = $request->input('jenis', '');
        $semester = $request->input('semester', $semesterList->first() ?? '');

        if ($semester && ! $semesterList->contains($semester)) {
            $semester = $semesterList->first() ?? '';
        }

        $query = Nilai::with('siswa.user', 'siswa.kelas', 'mapel', 'ujian');

        if ($kelasId) {
            $query->whereHas('siswa', fn ($q) => $q->where('kelas_id', $kelasId));
        }
        if ($mapelId) {
            $query->where('mapel_id', $mapelId);
        }
        if ($jenis) {
            $query->where('jenis', $jenis);
        }
        if ($semester) {
            $query->where('semester', $semester);
        }

        $semuaNilai = $query->latest()->paginate(20)->withQueryString();

        $daftarSiswa = Siswa::with('user', 'kelas')
            ->when($kelasId, fn ($q) => $q->where('kelas_id', $kelasId))
            ->orderBy('nisn')
            ->get();
        $daftarUjian = Ujian::with('mapel')
            ->when($mapelId, fn ($q) => $q->where('mapel_id', $mapelId))
            ->orderBy('nama')
            ->get();

        // Stats
        $totalNilai = Nilai::count();
        $rataRata = Nilai::avg('skor');
        $tertinggi = Nilai::max('skor');
        $terendah = Nilai::min('skor');

        return view('admin.nilai', compact(
            'semuaNilai', 'kelasList', 'mapelList', 'semesterList',
            'daftarSiswa', 'daftarUjian',
            'kelasId', 'mapelId', 'jenis', 'semester',
            'totalNilai', 'rataRata', 'tertinggi', 'terendah'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'ujian_id' => 'nullable|exists:ujian,id',
            'mapel_id' => 'required|exists:mapel,id',
            'jenis' => 'required|string|max:50',
            'semester' => 'required|string|max:50',
            'skor' => 'required|numeric|min:0|max:100',
            'benar' => 'nullable|integer|min:0',
            'salah' => 'nullable|integer|min:0',
        ]);

        $nilai = Nilai::create($validated);

        activity()->causedBy($request->user())->performedOn($nilai)->event('created')->log('Menambahkan nilai');

        return redirect()->route('admin.nilai')->with('success', 'Nilai berhasil ditambahkan.');
    }

    public function update(Request $request, Nilai $nilai)
    {
        $validated = $request->validate([
            'ujian_id' => 'nullable|exists:ujian,id',
            'mapel_id' => 'required|exists:mapel,id',
            'jenis' => 'required|string|max:50',
            'semester' => 'required|string|max:50',
            'skor' => 'required|numeric|min:0|max:100',
            'benar' => 'nullable|integer|min:0',
            'salah' => 'nullable|integer|min:0',
        ]);

        $nilai->update($validated);

        activity()->causedBy($request->user())->performedOn($nilai)->event('updated')->log('Memperbarui nilai');

        return redirect()->route('admin.nilai')->with('success', 'Nilai berhasil diperbarui.');
    }

    public function destroy(Request $request, Nilai $nilai)
    {
        $nilai->delete();

        activity()->causedBy($request->user())->performedOn($nilai)->event('deleted')->log('Menghapus nilai');

        return redirect()->route('admin.nilai')->with('success', 'Nilai berhasil dihapus.');
    }
}
