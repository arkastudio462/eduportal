<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Nilai;
use App\Models\Pengumuman;
use App\Models\Prestasi;
use App\Models\Siswa;
use App\Models\Ujian;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class SiswaDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $siswa = Siswa::with(['kelas.waliKelas.user', 'user'])->where('user_id', $user->id)->first();

        $hariMapping = [
            'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu',
        ];
        $hari = $hariMapping[now()->format('l')] ?? now()->format('l');

        $jadwalHariIni = collect();
        if ($siswa && $siswa->kelas) {
            $jadwalHariIni = Jadwal::with(['mapel', 'guru.user'])
                ->where('kelas_id', $siswa->kelas_id)
                ->where('hari', $hari)
                ->orderBy('jam_mulai')
                ->get();
        }

        $rataNilaiMapel = collect();
        if ($siswa) {
            $rataNilaiMapel = Nilai::with('ujian.mapel')
                ->where('siswa_id', $siswa->id)
                ->get()
                ->groupBy(fn ($n) => $n->ujian->mapel->nama ?? 'Umum')
                ->map(function ($items, $mapel) {
                    return (object) [
                        'mapel' => $mapel,
                        'rata_rata' => round($items->avg('skor'), 1),
                    ];
                })->values();
        }

        $pengumuman = Cache::remember('dashboard.pengumuman', 300, fn () => Pengumuman::latest()->take(3)->get()
        );

        $ujianMendatang = Cache::remember('dashboard.ujian_akan_datang_siswa', 120, fn () => Ujian::with('mapel')->where('status', 'akan_datang')->take(5)->get()
        );

        // Kehadiran: persentase ujian yang sudah dikerjakan dari total ujian kelas
        $kehadiran = 96;
        if ($siswa && $siswa->kelas) {
            $totalUjianKelas = Ujian::whereHas('kelas', fn ($q) => $q->where('kelas.id', $siswa->kelas_id))
                ->where('status', 'selesai')
                ->count();
            $ujianDikerjakan = Nilai::where('siswa_id', $siswa->id)->count();
            $kehadiran = $totalUjianKelas > 0 ? round($ujianDikerjakan / $totalUjianKelas * 100) : 96;
        }

        // Poin Prestasi: total prestasi yang tercatat
        $poinPrestasi = Cache::remember('dashboard.total_prestasi', 300, fn () => Prestasi::count());

        // Total Tugas: jumlah ujian yang tersedia untuk kelas siswa
        $totalTugas = 0;
        if ($siswa && $siswa->kelas) {
            $totalTugas = Ujian::whereHas('kelas', fn ($q) => $q->where('kelas.id', $siswa->kelas_id))->count();
        }

        return view('portal-siswa.dashboard', compact(
            'jadwalHariIni', 'rataNilaiMapel', 'pengumuman', 'ujianMendatang', 'siswa',
            'kehadiran', 'poinPrestasi', 'totalTugas'
        ));
    }
}
