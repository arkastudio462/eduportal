<?php

namespace App\Http\Controllers\Portal\Guru;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Modul;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ModulController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $guru = Guru::where('user_id', $user->id)->first();

        if (! $guru) {
            return view('portal-guru.modul', [
                'moduls' => collect(),
                'kelasList' => collect(),
                'selectedKelas' => null,
            ]);
        }

        $kelasIds = Jadwal::where('guru_id', $guru->id)->pluck('kelas_id')->unique();
        $kelasList = Kelas::whereIn('id', $kelasIds)->orderBy('nama')->get();

        $query = Modul::with('mapel', 'kelas')->where('guru_id', $guru->id);

        if ($request->kelas_id) {
            $query->where('kelas_id', $request->kelas_id);
        }

        $moduls = $query->latest()->get();

        return view('portal-guru.modul', compact('moduls', 'kelasList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:2000',
            'kelas_id' => 'nullable|exists:kelas,id',
            'file' => 'required|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,zip,rar|max:20480',
        ]);

        $user = Auth::user();
        $guru = Guru::where('user_id', $user->id)->first();

        if (! $guru) {
            return back()->with('error', 'Data guru tidak ditemukan.');
        }

        $mapel = Mapel::where('nama', $guru->mata_pelajaran)->first();

        if (! $mapel) {
            return back()->with('error', 'Mata pelajaran tidak ditemukan.');
        }

        $file = $request->file('file');
        $path = $file->store('moduls', 'public');

        Modul::create([
            'guru_id' => $guru->id,
            'mapel_id' => $mapel->id,
            'kelas_id' => $validated['kelas_id'],
            'judul' => $validated['judul'],
            'deskripsi' => $validated['deskripsi'],
            'file' => $path,
            'ekstensi' => $file->getClientOriginalExtension(),
            'ukuran' => $file->getSize(),
        ]);

        return redirect()->route('portal-guru.modul')->with('success', 'Modul berhasil diunggah.');
    }

    public function destroy(Modul $modul)
    {
        $guru = Guru::where('user_id', Auth::user()->id)->first();

        if (! $guru || $modul->guru_id !== $guru->id) {
            abort(403);
        }

        Storage::disk('public')->delete($modul->file);
        $modul->delete();

        return redirect()->route('portal-guru.modul')->with('success', 'Modul berhasil dihapus.');
    }

    public function download(Modul $modul)
    {
        if (! Storage::disk('public')->exists($modul->file)) {
            abort(404);
        }

        return Storage::disk('public')->download($modul->file);
    }
}
