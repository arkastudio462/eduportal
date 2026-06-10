<?php

namespace App\Http\Controllers\Portal\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Siswa;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class JadwalController extends Controller
{
    public function index()
    {
        $siswa = Siswa::where('user_id', Auth::id())->first();

        $hariMap = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
        $semuaJadwal = collect();

        if ($siswa) {
            $semuaJadwal = Jadwal::with(['mapel', 'guru.user'])
                ->where('kelas_id', $siswa->kelas_id)
                ->get()
                ->groupBy('hari');
        }

        return view('portal-siswa.jadwal', compact('semuaJadwal', 'hariMap'));
    }

    public function print()
    {
        $siswa = Siswa::where('user_id', Auth::id())->first();
        abort_unless($siswa, 404);

        $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $selectedKelas = $siswa->kelas;
        $semuaJadwal = Jadwal::with(['mapel', 'guru.user', 'kelas'])
            ->where('kelas_id', $siswa->kelas_id)
            ->orderBy('hari')->orderBy('jam_mulai')
            ->get();

        $title = 'Jadwal Pelajaran - ' . ($selectedKelas?->nama ?? '');

        $pdf = Pdf::loadView('pdf.jadwal', compact('hariList', 'title', 'semuaJadwal', 'selectedKelas'));
        return $pdf->stream('jadwal-pelajaran.pdf');
    }
}
