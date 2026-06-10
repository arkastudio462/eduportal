<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\PembayaranSpp;
use App\Models\Pengumuman;
use App\Models\Siswa;
use App\Models\Ujian;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index()
    {
        Carbon::setLocale('id');

        $totalSiswaAktif = Cache::remember('dashboard.total_siswa_aktif', 300, fn () => Siswa::aktif()->count());
        $totalGuru = Cache::remember('dashboard.total_guru', 300, fn () => Guru::count());
        $totalKelas = Cache::remember('dashboard.total_kelas', 300, fn () => Kelas::count());
        $kehadiranHariIni = 94;

        $siswaBaru = Cache::remember('dashboard.siswa_baru', 120, fn () => Siswa::with(['user', 'kelas'])->latest()->take(5)->get()
        );

        $ujianBerlangsung = Cache::remember('dashboard.ujian_berlangsung', 60, fn () => Ujian::with('mapel')->where('status', 'sedang_berlangsung')->take(3)->get()
        );

        $pembayaranTerbaru = Cache::remember('dashboard.pembayaran_terbaru', 300, fn () => PembayaranSpp::with('siswa.user')->latest()->take(3)->get()
        );

        $pengumuman = Cache::remember('dashboard.pengumuman', 300, fn () => Pengumuman::latest()->take(3)->get()
        );

        // Grafik mingguan: jumlah jadwal per hari
        $hariMap = ['Senin' => 'Sen', 'Selasa' => 'Sel', 'Rabu' => 'Rab', 'Kamis' => 'Kam', "Jum'at" => 'Jum'];
        $weeklyChart = Cache::remember('dashboard.weekly_chart', 300, function () use ($hariMap) {
            $jadwalPerHari = Jadwal::selectRaw('hari, count(*) as total')
                ->groupBy('hari')
                ->pluck('total', 'hari');
            $maxJadwal = max($jadwalPerHari->toArray() ?: [1]);
            $chart = [];
            foreach ($hariMap as $hariFull => $hariShort) {
                $count = $jadwalPerHari[$hariFull] ?? 0;
                $chart[$hariShort] = [
                    'count' => $count,
                    'pct' => $maxJadwal > 0 ? round($count / $maxJadwal * 100) : 0,
                ];
            }

            return $chart;
        });

        // Distribusi kelas per tingkat
        $distribusiKelas = Cache::remember('dashboard.distribusi_kelas', 300, function () {
            $kelasByTingkat = Kelas::selectRaw('tingkat, count(*) as total')
                ->groupBy('tingkat')
                ->orderBy('tingkat')
                ->pluck('total', 'tingkat');
            $totalAllKelas = array_sum($kelasByTingkat->toArray()) ?: 1;
            $colors = ['bg-primary', 'bg-secondary-container', 'bg-tertiary-fixed-dim'];
            $dist = [];
            $i = 0;
            foreach ($kelasByTingkat as $tingkat => $total) {
                $dist[] = [
                    'label' => 'Kelas '.$tingkat,
                    'pct' => round($total / $totalAllKelas * 100),
                    'color' => $colors[$i % 3],
                ];
                $i++;
            }

            return $dist;
        });

        $tanggalSekarang = now()->isoFormat('dddd, D MMMM YYYY');

        return view('admin.dashboard', compact(
            'totalSiswaAktif', 'totalGuru', 'totalKelas', 'kehadiranHariIni',
            'siswaBaru', 'ujianBerlangsung', 'pembayaranTerbaru', 'pengumuman',
            'weeklyChart', 'distribusiKelas', 'tanggalSekarang'
        ));
    }
}
