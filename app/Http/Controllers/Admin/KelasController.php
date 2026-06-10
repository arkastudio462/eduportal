<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Jurusan;
use App\Models\Kelas;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function show(Kelas $kelas)
    {
        $kelas->load(['waliKelas.user', 'jurusanRel']);

        return response()->json($kelas);
    }

    public function index()
    {
        $semuaKelas = Kelas::with('waliKelas.user', 'jurusanRel')->withCount('siswa')->latest()->get();
        $semuaGuru = Guru::with('user')->orderBy('id')->get();
        $semuaJurusan = Jurusan::orderBy('nama')->get();

        return view('admin.kelas', compact('semuaKelas', 'semuaGuru', 'semuaJurusan'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'tingkat' => 'required|string|max:50',
            'jurusan_id' => 'required|exists:jurusan,id',
            'wali_kelas_id' => 'nullable|exists:guru,id',
        ]);

        Kelas::create($validated);

        return redirect()->route('admin.kelas')->with('success', 'Kelas berhasil ditambahkan.');
    }

    public function update(Request $request, Kelas $kelas)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'tingkat' => 'required|string|max:50',
            'jurusan_id' => 'required|exists:jurusan,id',
            'wali_kelas_id' => 'nullable|exists:guru,id',
        ]);

        $kelas->update($validated);

        return redirect()->route('admin.kelas')->with('success', 'Kelas berhasil diperbarui.');
    }

    public function destroy(Kelas $kelas)
    {
        $kelas->delete();

        return redirect()->route('admin.kelas')->with('success', 'Kelas berhasil dihapus.');
    }
}
