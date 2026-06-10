<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Nilai;
use App\Models\PembayaranSpp;
use App\Models\Siswa;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index()
    {
        $totalSiswa = Siswa::aktif()->count();
        $totalGuru = Guru::count();
        $totalKelas = Kelas::count();
        $totalMapel = Mapel::count();

        $siswaPerKelas = Kelas::withCount(['siswa' => fn($q) => $q->aktif()])
            ->orderBy('tingkat')->orderBy('nama')
            ->get()
            ->map(fn($k) => ['label' => $k->nama, 'count' => $k->siswa_count]);

        $siswaPerJurusan = DB::table('siswa')
            ->join('kelas', 'siswa.kelas_id', '=', 'kelas.id')
            ->join('jurusan', 'kelas.jurusan_id', '=', 'jurusan.id')
            ->where('siswa.status', 'aktif')
            ->select('jurusan.nama', DB::raw('count(*) as total'))
            ->groupBy('jurusan.nama')
            ->get();

        $absensiPerBulan = Absensi::selectRaw("strftime('%m', tanggal) as bulan, status, count(*) as total")
            ->whereRaw("strftime('%Y', tanggal) = strftime('%Y', 'now')")
            ->groupBy('bulan', 'status')
            ->orderBy('bulan')
            ->get();

        $bulanNama = ['', 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $chartAbsensi = collect(range(1, 12))->map(fn($b) => [
            'bulan' => $bulanNama[$b],
            'hadir' => $absensiPerBulan->where('bulan', str_pad($b, 2, '0', STR_PAD_LEFT))->where('status', 'hadir')->sum('total'),
            'izin' => $absensiPerBulan->where('bulan', str_pad($b, 2, '0', STR_PAD_LEFT))->where('status', 'izin')->sum('total'),
            'sakit' => $absensiPerBulan->where('bulan', str_pad($b, 2, '0', STR_PAD_LEFT))->where('status', 'sakit')->sum('total'),
            'alfa' => $absensiPerBulan->where('bulan', str_pad($b, 2, '0', STR_PAD_LEFT))->whereIn('status', ['alfa', 'tanpa_keterangan'])->sum('total'),
        ]);

        $sppStats = PembayaranSpp::selectRaw("status, count(*) as total")
            ->groupBy('status')
            ->get()
            ->pluck('total', 'status');

        $rataNilai = Nilai::selectRaw("mapel_id, avg(skor) as rata_rata")
            ->groupBy('mapel_id')
            ->with('mapel')
            ->get()
            ->map(fn($n) => ['mapel' => $n->mapel?->nama ?? '-', 'rata' => round($n->rata_rata, 1)]);

        $kelasList = Kelas::orderBy('tingkat')->orderBy('nama')->get();
        $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', "Jum'at", 'Sabtu'];

        return view('admin.laporan', compact(
            'totalSiswa', 'totalGuru', 'totalKelas', 'totalMapel',
            'siswaPerKelas', 'siswaPerJurusan', 'chartAbsensi',
            'sppStats', 'rataNilai', 'kelasList', 'hariList'
        ));
    }

    public function exportSiswaPdf()
    {
        $semuaSiswa = Siswa::with('user', 'kelas')->aktif()->orderBy('kelas_id')->get();
        $pdf = Pdf::loadView('pdf.data-siswa', compact('semuaSiswa'));
        return $pdf->stream('data-siswa.pdf');
    }

    public function exportGuruPdf()
    {
        $semuaGuru = Guru::with('user')->orderBy('mata_pelajaran')->get();
        $pdf = Pdf::loadView('pdf.data-guru', compact('semuaGuru'));
        return $pdf->stream('data-guru.pdf');
    }

    public function exportJadwalPdf(Request $request)
    {
        $query = Jadwal::with('kelas', 'mapel', 'guru.user');
        if ($request->kelas_id) {
            $query->where('kelas_id', $request->kelas_id);
        }
        $semuaJadwal = $query->orderBy('hari')->orderBy('jam_mulai')->get();
        $kelasList = Kelas::orderBy('tingkat')->orderBy('nama')->get();
        $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', "Jum'at", 'Sabtu'];
        $selectedKelas = $request->kelas_id ? Kelas::find($request->kelas_id) : null;

        $kelompok = collect($hariList)->mapWithKeys(fn($hari) => [
            $hari => $semuaJadwal->where('hari', $hari)->sortBy('jam_mulai')
        ]);

        $title = 'Jadwal Pelajaran';
        if ($selectedKelas) {
            $title .= ' - ' . $selectedKelas->nama;
        }

        $pdf = Pdf::loadView('pdf.jadwal', compact('kelompok', 'hariList', 'title', 'semuaJadwal', 'selectedKelas'));
        return $pdf->stream('jadwal-pelajaran.pdf');
    }

    public function exportNilaiPdf(Request $request)
    {
        $query = Nilai::with('siswa.user', 'mapel');
        if ($request->kelas_id) {
            $query->whereHas('siswa', fn($q) => $q->where('kelas_id', $request->kelas_id));
        }
        if ($request->mapel_id) {
            $query->where('mapel_id', $request->mapel_id);
        }
        $semuaNilai = $query->orderBy('siswa_id')->get();
        $kelasList = Kelas::orderBy('tingkat')->orderBy('nama')->get();
        $mapelList = Mapel::orderBy('nama')->get();

        $pdf = Pdf::loadView('pdf.nilai', compact('semuaNilai', 'kelasList', 'mapelList'));
        return $pdf->stream('data-nilai.pdf');
    }

    public function exportAbsensiPdf(Request $request)
    {
        $query = Absensi::with('siswa.user', 'jadwal.mapel');
        if ($request->kelas_id) {
            $query->whereHas('siswa', fn($q) => $q->where('kelas_id', $request->kelas_id));
        }
        if ($request->bulan) {
            $query->whereMonth('tanggal', $request->bulan);
        }
        if ($request->tahun) {
            $query->whereYear('tanggal', $request->tahun);
        }
        $semuaAbsensi = $query->orderBy('tanggal')->get();
        $kelasList = Kelas::orderBy('tingkat')->orderBy('nama')->get();

        $pdf = Pdf::loadView('pdf.absensi', compact('semuaAbsensi', 'kelasList'));
        return $pdf->stream('data-absensi.pdf');
    }

    public function exportRaporPdf(Request $request, Siswa $siswa)
    {
        $semesterId = $request->semester_id;
        $semester = \App\Models\Semester::find($semesterId);
        $semesterLabel = $semester
            ? ($semester->tahun_ajaran . ' ' . $semester->semester)
            : $semesterId;

        $siswa->load('user', 'kelas.waliKelas.user');

        $nilaiPerMapel = Nilai::with('mapel')->where('siswa_id', $siswa->id)
            ->where('semester', $semesterLabel)
            ->get()
            ->groupBy('mapel_id');

        $mapelData = [];
        foreach ($nilaiPerMapel as $mapelId => $items) {
            $uh = $items->where('jenis', 'uh')->avg('skor');
            $tugas = $items->where('jenis', 'tugas')->avg('skor');
            $uts = $items->where('jenis', 'uts')->first()?->skor;
            $uas = $items->where('jenis', 'uas')->first()?->skor;
            $na = $this->hitungNilaiAkhir($uh, $tugas, $uts, $uas);
            $mapel = $items->first()->mapel;
            $mapelData[] = [
                'mapel' => $mapel->nama ?? '-',
                'uh' => $uh ? round($uh, 2) : null,
                'tugas' => $tugas ? round($tugas, 2) : null,
                'uts' => $uts,
                'uas' => $uas,
                'na' => $na,
                'huruf' => $this->konversiKeHuruf($na),
                'deskripsi' => $items->first()?->deskripsi ?? $this->generateDeskripsi($na, $mapel->nama ?? ''),
            ];
        }

        $totalNilai = collect($mapelData)->sum('na');
        $countMapel = collect($mapelData)->filter(fn($m) => $m['na'] !== null)->count();
        $rataRata = $countMapel > 0 ? round($totalNilai / $countMapel, 2) : null;

        $pdf = Pdf::loadView('pdf.rapor', compact('siswa', 'mapelData', 'rataRata', 'semesterLabel'));
        return $pdf->stream('rapor-' . $siswa->nisn . '.pdf');
    }

    private function hitungNilaiAkhir(?float $uh, ?float $tugas, ?float $uts, ?float $uas): ?float
    {
        $bobotUh = $uh ? $uh * 0.3 : 0;
        $bobotTugas = $tugas ? $tugas * 0.2 : 0;
        $bobotUts = $uts ? $uts * 0.25 : 0;
        $bobotUas = $uas ? $uas * 0.25 : 0;
        $total = $bobotUh + $bobotTugas + $bobotUts + $bobotUas;
        $pembagi = ($uh ? 0.3 : 0) + ($tugas ? 0.2 : 0) + ($uts ? 0.25 : 0) + ($uas ? 0.25 : 0);
        return $pembagi > 0 ? round($total / $pembagi, 2) : null;
    }

    private function konversiKeHuruf(?float $nilai): string
    {
        if ($nilai === null) return '-';
        return match (true) {
            $nilai >= 92 => 'A',
            $nilai >= 83 => 'B',
            $nilai >= 75 => 'C',
            $nilai >= 60 => 'D',
            default => 'E',
        };
    }

    private function generateDeskripsi(?float $nilai, string $mapel): string
    {
        if ($nilai === null) return 'Belum ada nilai';
        return match (true) {
            $nilai >= 92 => "Sangat baik dalam menguasai materi $mapel secara keseluruhan.",
            $nilai >= 83 => "Baik dalam menguasai materi $mapel, perlu sedikit peningkatan.",
            $nilai >= 75 => "Cukup baik dalam menguasai materi $mapel, perlu lebih giat belajar.",
            $nilai >= 60 => "Kurang dalam menguasai materi $mapel, perlu bimbingan intensif.",
            default => "Sangat kurang dalam menguasai materi $mapel, perlu remedial.",
        };
    }
}
