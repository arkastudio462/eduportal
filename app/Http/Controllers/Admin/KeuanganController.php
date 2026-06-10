<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\PembayaranSpp;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class KeuanganController extends Controller
{
    public function show(PembayaranSpp $keuangan)
    {
        $keuangan->load('siswa.user');

        return response()->json($keuangan);
    }

    public function index(Request $request)
    {
        $kelasList = Cache::remember('daftar_kelas', 86400, fn () => Kelas::orderBy('tingkat')->orderBy('nama')->get());

        $bulanAktif = $request->input('bulan', date('m'));
        $tahunAktif = $request->input('tahun', date('Y'));
        $kelasId = $request->input('kelas_id');
        $statusFilter = $request->input('status', '');

        $query = PembayaranSpp::with('siswa.user', 'siswa.kelas')
            ->where('bulan', $bulanAktif)
            ->where('tahun', $tahunAktif);

        if ($kelasId) {
            $query->whereHas('siswa', fn ($q) => $q->where('kelas_id', $kelasId));
        }
        if ($statusFilter) {
            $query->where('status', $statusFilter);
        }

        $pembayaran = $query->latest()->get();

        $totalSudah = PembayaranSpp::where('bulan', $bulanAktif)->where('tahun', $tahunAktif)->where('status', 'lunas')->sum('jumlah');
        $totalTunggakan = PembayaranSpp::where('bulan', $bulanAktif)->where('tahun', $tahunAktif)->where('status', 'belum')->sum('jumlah');
        $totalPembayaran = PembayaranSpp::where('bulan', $bulanAktif)->where('tahun', $tahunAktif)->count();
        $totalLunas = PembayaranSpp::where('bulan', $bulanAktif)->where('tahun', $tahunAktif)->where('status', 'lunas')->count();
        $persentase = $totalPembayaran > 0 ? round($totalLunas / $totalPembayaran * 100, 1) : 0;

        $siswaList = Siswa::aktif()->with('user', 'kelas')->orderBy('nisn')->get();

        return view('admin.keuangan', compact(
            'pembayaran', 'kelasList', 'siswaList',
            'bulanAktif', 'tahunAktif', 'kelasId', 'statusFilter',
            'totalSudah', 'totalTunggakan', 'totalPembayaran', 'totalLunas', 'persentase'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'bulan' => 'required|string|max:2',
            'tahun' => 'required|string|max:4',
            'jumlah' => 'required|numeric|min:0',
            'status' => 'required|in:lunas,belum,angsuran',
            'tanggal_bayar' => 'nullable|date',
        ]);

        PembayaranSpp::create($validated);

        return redirect()->route('admin.keuangan')->with('success', 'Pembayaran berhasil dicatat.');
    }

    public function update(Request $request, PembayaranSpp $keuangan)
    {
        $validated = $request->validate([
            'jumlah' => 'required|numeric|min:0',
            'status' => 'required|in:lunas,belum,angsuran',
            'tanggal_bayar' => 'nullable|date',
        ]);

        $keuangan->update($validated);

        return redirect()->route('admin.keuangan')->with('success', 'Pembayaran berhasil diperbarui.');
    }

    public function destroy(PembayaranSpp $keuangan)
    {
        $keuangan->delete();

        return redirect()->route('admin.keuangan')->with('success', 'Pembayaran berhasil dihapus.');
    }
}
