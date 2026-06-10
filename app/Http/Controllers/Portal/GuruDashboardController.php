<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\Nilai;
use App\Models\Pengumuman;
use App\Models\Siswa;
use App\Models\Ujian;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class GuruDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $guru = Guru::with('user', 'kelasWali.jurusanRel')->where('user_id', $user->id)->first();

        $hariMapping = [
            'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu',
        ];
        $hari = $hariMapping[now()->format('l')] ?? now()->format('l');

        $jadwalHariIni = collect();
        $kelasDiampu = collect();
        $perbandinganNilai = collect();

        if ($guru) {
            $jadwalHariIni = Jadwal::with(['mapel', 'kelas'])
                ->where('guru_id', $guru->id)
                ->where('hari', $hari)
                ->orderBy('jam_mulai')
                ->get();

            $kelasIds = $jadwalHariIni->pluck('kelas_id')->unique();
            $kelasDiampu = Kelas::whereIn('id', $kelasIds)->get();

            $perbandinganNilai = Nilai::with(['ujian.mapel', 'siswa.kelas'])
                ->whereHas('ujian', fn ($q) => $q->whereHas('kelas', fn ($k) => $k->whereIn('kelas.id', $kelasIds)))
                ->get()
                ->groupBy(fn ($n) => $n->siswa->kelas->nama ?? 'Unknown')
                ->map(function ($items) {
                    return [
                        'kelas' => $items->first()->siswa->kelas->nama ?? 'Unknown',
                        'rata_rata' => round($items->avg('skor'), 1),
                        'tertinggi' => $items->max('skor'),
                        'terendah' => $items->min('skor'),
                        'lulus' => $items->where('skor', '>=', 75)->count(),
                        'tidak_lulus' => $items->where('skor', '<', 75)->count(),
                        'total' => $items->count(),
                    ];
                })->values();
        }

        $totalSiswa = $kelasDiampu->isNotEmpty()
            ? Siswa::whereIn('kelas_id', $kelasDiampu->pluck('id'))->count()
            : 0;

        // Tugas Aktif: ujian berlangsung/akan datang untuk mapel guru
        $tugasAktif = 0;
        if ($guru) {
            $tugasAktif = Ujian::whereHas('mapel', fn ($q) => $q->where('nama', $guru->mata_pelajaran))
                ->whereIn('status', ['sedang_berlangsung', 'akan_datang'])
                ->count();
        }

        // Aktivitas Terkini: 4 pengumuman terbaru
        $aktivitasTerkini = Cache::remember('dashboard.pengumuman_terkini', 300, fn () => Pengumuman::latest()->take(4)->get()
        );

        // Tugas yang Periksa: persentase penilaian per ujian
        $tugasPeriksa = collect();
        if ($guru) {
            $ujianGuru = Ujian::withCount('nilai')
                ->with('kelas')
                ->whereHas('mapel', fn ($q) => $q->where('nama', $guru->mata_pelajaran))
                ->where('status', 'selesai')
                ->take(3)
                ->get();

            foreach ($ujianGuru as $ujian) {
                $totalSiswaUjian = $ujian->kelas->sum(fn ($k) => $k->siswa()->count());
                $tugasPeriksa->push([
                    'nama' => $ujian->nama,
                    'sudah' => $ujian->nilai_count,
                    'total' => max($totalSiswaUjian, $ujian->nilai_count),
                ]);
            }
        }

        // Agenda Mendatang: 3 ujian akan datang
        $agendaMendatang = Cache::remember('dashboard.ujian_akan_datang', 120, fn () => Ujian::with('mapel')->where('status', 'akan_datang')->take(3)->get()
        );

        return view('portal-guru.dashboard', compact(
            'guru', 'jadwalHariIni', 'kelasDiampu', 'perbandinganNilai', 'totalSiswa',
            'tugasAktif', 'aktivitasTerkini', 'tugasPeriksa', 'agendaMendatang'
        ));
    }
}
