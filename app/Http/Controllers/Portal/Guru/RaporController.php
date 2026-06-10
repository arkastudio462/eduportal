<?php

namespace App\Http\Controllers\Portal\Guru;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Nilai;
use App\Models\Siswa;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RaporController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $guru = Guru::where('user_id', $user->id)->first();

        if (! $guru) {
            return view('portal-guru.rapor', [
                'kelasList' => collect(),
                'siswaList' => collect(),
                'selectedKelas' => null,
            ]);
        }

        $kelasIds = Jadwal::where('guru_id', $guru->id)->pluck('kelas_id')->unique();
        $kelasList = Kelas::whereIn('id', $kelasIds)
            ->orWhere('wali_kelas_id', $guru->id)
            ->orderBy('tingkat')
            ->orderBy('nama')
            ->get();

        $selectedKelas = $request->kelas_id ? Kelas::find($request->kelas_id) : $kelasList->first();

        $siswaList = collect();
        if ($selectedKelas) {
            $siswaList = Siswa::with('user')
                ->where('kelas_id', $selectedKelas->id)
                ->aktif()
                ->orderBy(User::select('name')->whereColumn('users.id', 'siswa.user_id'))
                ->get();
        }

        return view('portal-guru.rapor', compact('kelasList', 'siswaList', 'selectedKelas'));
    }

    public function print(Request $request, Siswa $siswa)
    {
        $user = Auth::user();
        $guru = Guru::where('user_id', $user->id)->first();

        if (! $guru) {
            abort(403);
        }

        $siswa->load('user', 'kelas.waliKelas.user');

        $semester = $request->semester ?? (now()->month >= 7 ? 'Ganjil' : 'Genap');
        $tahunAjar = $request->tahun_ajar ?? now()->year;

        $mapelDiampu = Mapel::where('nama', $guru->mata_pelajaran)->pluck('id');
        $kelasIds = Jadwal::where('guru_id', $guru->id)->pluck('kelas_id')->unique()->toArray();
        $allowedKelas = array_merge($kelasIds, [$siswa->kelas?->wali_kelas_id === $guru->id ? $siswa->kelas_id : null]);
        $allowedKelas = array_filter($allowedKelas);

        if (! in_array($siswa->kelas_id, $allowedKelas)) {
            abort(403);
        }

        $nilaiList = Nilai::with('mapel')
            ->where('siswa_id', $siswa->id)
            ->where('semester', $semester)
            ->where(function ($q) use ($mapelDiampu) {
                $q->whereIn('mapel_id', $mapelDiampu)
                    ->orWhere('jenis', 'uts')
                    ->orWhere('jenis', 'uas');
            })
            ->get()
            ->groupBy(fn ($item) => $item->mapel?->nama ?? 'Lainnya');

        $absensi = Absensi::where('siswa_id', $siswa->id)
            ->whereBetween('tanggal', [
                now()->setMonth($semester === 'Ganjil' ? 7 : 1)->startOfMonth(),
                now()->setMonth($semester === 'Ganjil' ? 12 : 6)->endOfMonth(),
            ])
            ->get();

        $kehadiran = [
            'hadir' => $absensi->where('status', 'hadir')->count(),
            'sakit' => $absensi->where('status', 'sakit')->count(),
            'izin' => $absensi->where('status', 'izin')->count(),
            'alpha' => $absensi->where('status', 'alpha')->count(),
        ];

        $rataRata = Nilai::where('siswa_id', $siswa->id)
            ->where('semester', $semester)
            ->avg('skor');

        $pdf = Pdf::loadView('pdf.rapor', compact(
            'siswa', 'nilaiList', 'kehadiran', 'rataRata',
            'semester', 'tahunAjar'
        ));

        return $pdf->stream("rapor-{$siswa->nisn}.pdf");
    }
}
