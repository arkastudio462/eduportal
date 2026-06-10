<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Konseling;
use App\Models\Siswa;
use Illuminate\Http\Request;

class KonselingController extends Controller
{
    public function index(Request $request)
    {
        $query = Konseling::with('siswa.user', 'guru.user');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('guru_id')) {
            $query->where('guru_id', $request->guru_id);
        }

        $semuaKonseling = $query->latest()->paginate(20);
        $semuaGuru = Guru::with('user')->orderBy('id')->get();
        $kelasList = Kelas::orderBy('tingkat')->orderBy('nama')->get();

        return view('admin.konseling', compact('semuaKonseling', 'semuaGuru', 'kelasList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'guru_id' => 'required|exists:guru,id',
            'tanggal' => 'required|date',
            'jam' => 'nullable',
            'topik' => 'required|string|max:255',
            'catatan' => 'nullable|string',
            'tindak_lanjut' => 'nullable|string',
            'status' => 'required|in:dijadwalkan,selesai,dibatalkan',
        ]);

        Konseling::create($validated);

        return redirect()->route('admin.konseling')->with('success', 'Data konseling berhasil ditambahkan.');
    }

    public function update(Request $request, Konseling $konseling)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'jam' => 'nullable',
            'topik' => 'required|string|max:255',
            'catatan' => 'nullable|string',
            'tindak_lanjut' => 'nullable|string',
            'status' => 'required|in:dijadwalkan,selesai,dibatalkan',
        ]);

        $konseling->update($validated);

        return redirect()->route('admin.konseling')->with('success', 'Data konseling berhasil diperbarui.');
    }

    public function destroy(Konseling $konseling)
    {
        $konseling->delete();

        return redirect()->route('admin.konseling')->with('success', 'Data konseling berhasil dihapus.');
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
