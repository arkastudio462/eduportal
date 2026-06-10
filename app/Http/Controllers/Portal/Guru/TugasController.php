<?php

namespace App\Http\Controllers\Portal\Guru;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Siswa;
use App\Models\Tugas;
use App\Models\TugasSubmission;
use App\Notifications\TugasBaruNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Notification;

class TugasController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $guru = Guru::where('user_id', $user->id)->first();

        $semuaTugas = collect();
        $kelasList = collect();
        $mapelList = collect();

        if ($guru) {
            $kelasIds = Jadwal::where('guru_id', $guru->id)->pluck('kelas_id')->unique();
            $kelasList = Kelas::whereIn('id', $kelasIds)->orderBy('tingkat')->orderBy('nama')->get();
            $mapelList = Mapel::where('nama', $guru->mata_pelajaran)->get();
            if ($mapelList->isEmpty()) {
                $mapelList = Cache::remember('daftar_mapel', 86400, fn () => Mapel::select('id', 'nama')->orderBy('nama')->get());
            }

            $semuaTugas = Tugas::with(['mapel', 'kelas', 'submissions'])
                ->where('guru_id', $guru->id)
                ->latest()
                ->get();
        }

        return view('portal-guru.tugas', compact('semuaTugas', 'kelasList', 'mapelList', 'guru'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'mapel_id' => 'required|exists:mapel,id',
            'kelas_id' => 'required|exists:kelas,id',
            'deadline' => 'nullable|date',
        ]);

        $user = Auth::user();
        $guru = Guru::where('user_id', $user->id)->first();

        $tugas = Tugas::create([
            'guru_id' => $guru->id,
            'mapel_id' => $validated['mapel_id'],
            'kelas_id' => $validated['kelas_id'],
            'judul' => $validated['judul'],
            'deskripsi' => $validated['deskripsi'],
            'deadline' => $validated['deadline'],
        ]);

        $mapel = Mapel::find($validated['mapel_id']);
        $siswaList = Siswa::where('kelas_id', $validated['kelas_id'])->with('user')->get();
        $users = $siswaList->pluck('user')->filter();
        Notification::send($users, new TugasBaruNotification(
            $validated['judul'],
            $tugas->id,
            $mapel->nama,
        ));

        return redirect()->route('portal-guru.tugas')->with('success', 'Tugas berhasil dibuat.');
    }

    public function destroy(Tugas $tugas)
    {
        $this->authorize('delete', $tugas);

        $tugas->delete();

        return redirect()->route('portal-guru.tugas')->with('success', 'Tugas berhasil dihapus.');
    }

    public function submissions($id)
    {
        $tugas = Tugas::with(['mapel', 'kelas', 'submissions.siswa.user'])->findOrFail($id);

        return view('portal-guru.tugas-submissions', compact('tugas'));
    }

    public function nilai(Request $request, $id, $submissionId)
    {
        $validated = $request->validate([
            'nilai' => 'required|numeric|min:0|max:100',
        ]);

        $submission = TugasSubmission::findOrFail($submissionId);
        $submission->update(['nilai' => $validated['nilai']]);

        return redirect()->route('portal-guru.tugas.submissions', $id)
            ->with('success', 'Nilai berhasil disimpan.');
    }
}
