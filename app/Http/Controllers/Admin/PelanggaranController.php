<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriPelanggaran;
use App\Models\Kelas;
use App\Models\PelanggaranSiswa;
use Illuminate\Http\Request;

class PelanggaranController extends Controller
{
    public function index(Request $request)
    {
        $query = PelanggaranSiswa::with('siswa.user', 'kategori', 'dicatatOleh');

        if ($request->filled('kelas_id')) {
            $query->whereHas('siswa', fn ($q) => $q->where('kelas_id', $request->kelas_id));
        }

        $semuaPelanggaran = $query->latest()->paginate(20);
        $kategoriList = KategoriPelanggaran::orderBy('nama')->get();
        $kelasList = Kelas::orderBy('tingkat')->orderBy('nama')->get();

        return view('admin.pelanggaran-siswa', compact('semuaPelanggaran', 'kategoriList', 'kelasList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'kategori_id' => 'nullable|exists:kategori_pelanggaran,id',
            'tanggal' => 'required|date',
            'pelanggaran' => 'required|string',
            'poin' => 'required|integer|min:0',
            'sanksi' => 'nullable|string',
            'keterangan' => 'nullable|string',
        ]);

        $validated['dicatat_oleh'] = auth()->id();
        PelanggaranSiswa::create($validated);

        return redirect()->route('admin.pelanggaran-siswa')->with('success', 'Pelanggaran berhasil dicatat.');
    }

    public function update(Request $request, PelanggaranSiswa $pelanggaranSiswa)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'pelanggaran' => 'required|string',
            'poin' => 'required|integer|min:0',
            'sanksi' => 'nullable|string',
            'keterangan' => 'nullable|string',
        ]);

        $pelanggaranSiswa->update($validated);

        return redirect()->route('admin.pelanggaran-siswa')->with('success', 'Pelanggaran berhasil diperbarui.');
    }

    public function destroy(PelanggaranSiswa $pelanggaranSiswa)
    {
        $pelanggaranSiswa->delete();

        return redirect()->route('admin.pelanggaran-siswa')->with('success', 'Pelanggaran berhasil dihapus.');
    }

    // Kategori Pelanggaran
    public function storeKategori(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'poin' => 'required|integer|min:0',
            'sanksi' => 'nullable|string',
        ]);

        KategoriPelanggaran::create($validated);

        return redirect()->route('admin.pelanggaran-siswa')->with('success', 'Kategori pelanggaran berhasil ditambahkan.');
    }

    public function updateKategori(Request $request, KategoriPelanggaran $kategoriPelanggaran)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'poin' => 'required|integer|min:0',
            'sanksi' => 'nullable|string',
        ]);

        $kategoriPelanggaran->update($validated);

        return redirect()->route('admin.pelanggaran-siswa')->with('success', 'Kategori pelanggaran berhasil diperbarui.');
    }

    public function destroyKategori(KategoriPelanggaran $kategoriPelanggaran)
    {
        $kategoriPelanggaran->delete();

        return redirect()->route('admin.pelanggaran-siswa')->with('success', 'Kategori pelanggaran berhasil dihapus.');
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
