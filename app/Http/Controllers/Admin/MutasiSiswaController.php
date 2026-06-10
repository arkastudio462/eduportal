<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\MutasiSiswa;
use App\Models\Siswa;
use Illuminate\Http\Request;

class MutasiSiswaController extends Controller
{
    public function index(Request $request)
    {
        $query = MutasiSiswa::with('siswa.user');

        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        $semuaMutasi = $query->latest()->paginate(20);
        $kelasList = Kelas::orderBy('tingkat')->orderBy('nama')->get();

        return view('admin.mutasi-siswa', compact('semuaMutasi', 'kelasList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'jenis' => 'required|in:masuk,keluar,pindah',
            'tanggal' => 'required|date',
            'sekolah_asal' => 'nullable|string|max:255',
            'sekolah_tujuan' => 'nullable|string|max:255',
            'alasan' => 'nullable|string',
            'keterangan' => 'nullable|string',
        ]);

        $mutasi = MutasiSiswa::create($validated);

        // Update siswa status
        $siswa = Siswa::find($validated['siswa_id']);
        if ($validated['jenis'] === 'keluar' || $validated['jenis'] === 'pindah') {
            $siswa->update(['status' => 'alumni']);
        } elseif ($validated['jenis'] === 'masuk') {
            $siswa->update(['status' => 'aktif']);
        }

        return redirect()->route('admin.mutasi-siswa')->with('success', 'Data mutasi berhasil dicatat.');
    }

    public function destroy(MutasiSiswa $mutasiSiswa)
    {
        $mutasiSiswa->delete();

        return redirect()->route('admin.mutasi-siswa')->with('success', 'Data mutasi berhasil dihapus.');
    }

    public function getSiswaByKelas(Request $request)
    {
        $request->validate(['kelas_id' => 'required|exists:kelas,id']);

        $siswa = Siswa::with('user')
            ->where('kelas_id', $request->kelas_id)
            ->orderBy('id')
            ->get()
            ->map(fn ($s) => ['id' => $s->id, 'nama' => $s->user->name, 'nisn' => $s->nisn]);

        return response()->json($siswa);
    }
}
