<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AbsensiController extends Controller
{
    public function show(Absensi $absensi)
    {
        $absensi->load(['siswa.user', 'jadwal']);

        return response()->json($absensi);
    }

    public function index(Request $request)
    {
        $kelasList = Cache::remember('daftar_kelas', 86400, fn () => Kelas::orderBy('tingkat')->orderBy('nama')->get());

        $kelasId = $request->input('kelas_id');
        $tanggalMulai = $request->input('tanggal_mulai', now()->startOfMonth()->format('Y-m-d'));
        $tanggalSelesai = $request->input('tanggal_selesai', now()->format('Y-m-d'));
        $statusFilter = $request->input('status', '');

        $query = Absensi::with('siswa.user', 'siswa.kelas');

        if ($kelasId) {
            $query->whereHas('siswa', fn ($q) => $q->where('kelas_id', $kelasId));
        }
        if ($tanggalMulai) {
            $query->whereDate('tanggal', '>=', $tanggalMulai);
        }
        if ($tanggalSelesai) {
            $query->whereDate('tanggal', '<=', $tanggalSelesai);
        }
        if ($statusFilter) {
            $query->where('status', $statusFilter);
        }

        $semuaAbsensi = $query->latest('tanggal')->paginate(20)->withQueryString();

        $daftarSiswa = Siswa::with('user', 'kelas')
            ->when($kelasId, fn ($q) => $q->where('kelas_id', $kelasId))
            ->orderBy('nisn')
            ->get();

        // Stats
        $totalHadir = Absensi::where('status', 'hadir')
            ->when($kelasId, fn ($q) => $q->whereHas('siswa', fn ($sq) => $sq->where('kelas_id', $kelasId)))
            ->count();
        $totalSakit = Absensi::where('status', 'sakit')
            ->when($kelasId, fn ($q) => $q->whereHas('siswa', fn ($sq) => $sq->where('kelas_id', $kelasId)))
            ->count();
        $totalIzin = Absensi::where('status', 'izin')
            ->when($kelasId, fn ($q) => $q->whereHas('siswa', fn ($sq) => $sq->where('kelas_id', $kelasId)))
            ->count();
        $totalAlpha = Absensi::where('status', 'alpha')
            ->when($kelasId, fn ($q) => $q->whereHas('siswa', fn ($sq) => $sq->where('kelas_id', $kelasId)))
            ->count();

        return view('admin.absensi', compact(
            'semuaAbsensi', 'kelasList', 'daftarSiswa',
            'kelasId', 'tanggalMulai', 'tanggalSelesai', 'statusFilter',
            'totalHadir', 'totalSakit', 'totalIzin', 'totalAlpha'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'tanggal' => 'required|date',
            'status' => 'required|in:hadir,sakit,izin,alpha',
            'keterangan' => 'nullable|string|max:255',
        ]);

        Absensi::updateOrCreate(
            ['siswa_id' => $validated['siswa_id'], 'tanggal' => $validated['tanggal']],
            $validated
        );

        return redirect()->route('admin.absensi')->with('success', 'Absensi berhasil dicatat.');
    }

    public function update(Request $request, Absensi $absensi)
    {
        $validated = $request->validate([
            'status' => 'required|in:hadir,sakit,izin,alpha',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $absensi->update($validated);

        return redirect()->route('admin.absensi')->with('success', 'Absensi berhasil diperbarui.');
    }

    public function destroy(Absensi $absensi)
    {
        $absensi->delete();

        return redirect()->route('admin.absensi')->with('success', 'Absensi berhasil dihapus.');
    }
}
