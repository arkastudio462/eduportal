<?php

namespace App\Http\Controllers\Portal\Guru;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Ujian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UjianOnlineController extends Controller
{
    public function index()
    {
        $guru = Guru::where('user_id', Auth::id())->first();
        $ujianList = collect();
        $mapel = null;
        $kelasList = collect();

        if ($guru) {
            $mapel = Mapel::where('nama', $guru->mata_pelajaran)->first();

            $kelasIds = Jadwal::where('guru_id', $guru->id)
                ->pluck('kelas_id')->unique();
            $kelasList = Kelas::whereIn('id', $kelasIds)->orderBy('tingkat')->orderBy('nama')->get();

            $ujianList = Ujian::with('mapel', 'kelas')
                ->withCount('nilai as jumlah_peserta')
                ->when($mapel, fn ($q) => $q->where('mapel_id', $mapel->id))
                ->orderBy('tanggal_mulai', 'desc')
                ->get();
        }

        return view('portal-guru.ujian-online', compact('ujianList', 'mapel', 'kelasList'));
    }

    public function store(Request $request)
    {
        $guru = Guru::where('user_id', Auth::id())->first();
        $mapel = Mapel::where('nama', $guru->mata_pelajaran ?? '')->first();

        if (! $mapel) {
            return redirect()->route('portal-guru.ujian-online')->with('error', 'Mata pelajaran tidak ditemukan.');
        }

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'durasi' => 'required|integer|min:1',
            'status' => 'required|in:draft,akan_datang,sedang_berlangsung,selesai',
            'kelas_ids' => 'required|array',
            'kelas_ids.*' => 'exists:kelas,id',
        ]);

        $validated['mapel_id'] = $mapel->id;

        $ujian = Ujian::create($validated);
        $ujian->kelas()->attach($validated['kelas_ids']);

        return redirect()->route('portal-guru.ujian-online')->with('success', 'Ujian berhasil ditambahkan.');
    }

    public function update(Request $request, Ujian $ujian)
    {
        $this->authorize('update', $ujian);

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'durasi' => 'required|integer|min:1',
            'status' => 'required|in:draft,akan_datang,sedang_berlangsung,selesai',
            'kelas_ids' => 'required|array',
            'kelas_ids.*' => 'exists:kelas,id',
        ]);

        $ujian->update($validated);
        $ujian->kelas()->sync($validated['kelas_ids']);

        return redirect()->route('portal-guru.ujian-online')->with('success', 'Ujian berhasil diperbarui.');
    }

    public function destroy(Ujian $ujian)
    {
        $this->authorize('delete', $ujian);

        $ujian->kelas()->detach();
        $ujian->delete();

        return redirect()->route('portal-guru.ujian-online')->with('success', 'Ujian berhasil dihapus.');
    }
}
