<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ekskul;
use App\Models\EkskulAnggota;
use App\Models\Siswa;
use Illuminate\Http\Request;

class EkskulController extends Controller
{
    public function index()
    {
        $ekskuls = Ekskul::withCount('anggota')->latest()->get();

        return view('admin.ekskul', compact('ekskuls'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'pembina' => 'required|string|max:255',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
            'tempat' => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:2000',
            'kuota' => 'required|integer|min:1',
        ]);

        Ekskul::create($data);

        return redirect()->route('admin.ekskul')->with('success', 'Ekstrakurikuler berhasil ditambahkan.');
    }

    public function update(Request $request, Ekskul $ekskul)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'pembina' => 'required|string|max:255',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
            'tempat' => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:2000',
            'kuota' => 'required|integer|min:1',
        ]);

        $ekskul->update($data);

        return redirect()->route('admin.ekskul')->with('success', 'Ekstrakurikuler berhasil diperbarui.');
    }

    public function destroy(Ekskul $ekskul)
    {
        $ekskul->delete();

        return redirect()->route('admin.ekskul')->with('success', 'Ekstrakurikuler berhasil dihapus.');
    }

    public function anggota(Ekskul $ekskul)
    {
        $anggota = $ekskul->anggota()->with('user')->get();
        $siswaList = Siswa::with('user')->aktif()->whereDoesntHave('ekskuls', fn ($q) => $q->where('ekskul_id', $ekskul->id))->get();

        return view('admin.ekskul-anggota', compact('ekskul', 'anggota', 'siswaList'));
    }

    public function addAnggota(Request $request, Ekskul $ekskul)
    {
        $request->validate(['siswa_id' => 'required|exists:siswa,id']);

        $exists = EkskulAnggota::where('ekskul_id', $ekskul->id)
            ->where('siswa_id', $request->siswa_id)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Siswa sudah terdaftar di ekskul ini.');
        }

        if ($ekskul->anggota()->count() >= $ekskul->kuota) {
            return back()->with('error', 'Kuota ekskul sudah penuh.');
        }

        EkskulAnggota::create([
            'ekskul_id' => $ekskul->id,
            'siswa_id' => $request->siswa_id,
            'status' => 'aktif',
        ]);

        return back()->with('success', 'Anggota berhasil ditambahkan.');
    }

    public function removeAnggota(Ekskul $ekskul, Siswa $siswa)
    {
        EkskulAnggota::where('ekskul_id', $ekskul->id)
            ->where('siswa_id', $siswa->id)
            ->delete();

        return back()->with('success', 'Anggota berhasil dihapus.');
    }
}
