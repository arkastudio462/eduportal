<?php

namespace App\Http\Controllers\Portal\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AbsensiController extends Controller
{
    public function index(Request $request)
    {
        $siswa = Siswa::where('user_id', Auth::id())->first();
        if (! $siswa) {
            return view('portal-siswa.absensi', [
                'riwayat' => collect(),
                'bulanAktif' => null,
                'bulanList' => collect(),
                'stats' => ['hadir' => 0, 'sakit' => 0, 'izin' => 0, 'alpha' => 0, 'persentase' => 0],
            ]);
        }

        $driver = DB::connection()->getDriverName();
        $dateExpr = $driver === 'sqlite'
            ? "strftime('%Y-%m', tanggal)"
            : "DATE_FORMAT(tanggal, '%Y-%m')";
        $bulanList = Absensi::where('siswa_id', $siswa->id)
            ->selectRaw("$dateExpr as bulan")
            ->distinct()->orderBy('bulan', 'desc')->pluck('bulan');

        $bulanAktif = $request->input('bulan', $bulanList->first());

        $riwayat = Absensi::with('jadwal')
            ->where('siswa_id', $siswa->id)
            ->when($bulanAktif, function ($q) use ($bulanAktif) {
                [$tahun, $bulan] = explode('-', $bulanAktif);
                $q->whereYear('tanggal', $tahun)->whereMonth('tanggal', $bulan);
            })
            ->orderBy('tanggal', 'desc')
            ->get();

        $total = $riwayat->count();
        $stats = [
            'hadir' => $riwayat->where('status', 'hadir')->count(),
            'sakit' => $riwayat->where('status', 'sakit')->count(),
            'izin' => $riwayat->where('status', 'izin')->count(),
            'alpha' => $riwayat->where('status', 'alpha')->count(),
            'persentase' => $total > 0 ? round($riwayat->where('status', 'hadir')->count() / $total * 100) : 0,
        ];

        return view('portal-siswa.absensi', compact('riwayat', 'bulanAktif', 'bulanList', 'stats'));
    }
}
