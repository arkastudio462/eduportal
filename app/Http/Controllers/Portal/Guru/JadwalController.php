<?php

namespace App\Http\Controllers\Portal\Guru;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Jadwal;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class JadwalController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $guru = Guru::where('user_id', $user->id)->first();

        $hariMap = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

        $semuaJadwal = collect();
        if ($guru) {
            $semuaJadwal = Jadwal::with(['mapel', 'kelas'])
                ->where('guru_id', $guru->id)
                ->whereHas('mapel', fn ($q) => $q->where('nama', $guru->mata_pelajaran)
                    ->orWhere('nama', 'like', $guru->mata_pelajaran.'%')
                )
                ->get()
                ->groupBy('hari');
        }

        return view('portal-guru.jadwal', compact('semuaJadwal', 'hariMap', 'guru'));
    }

    public function print()
    {
        $user = Auth::user();
        $guru = Guru::where('user_id', $user->id)->first();
        abort_unless($guru, 404);

        $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $semuaJadwal = Jadwal::with(['mapel', 'kelas', 'guru.user'])
            ->where('guru_id', $guru->id)
            ->orderBy('hari')->orderBy('jam_mulai')
            ->get();

        $title = 'Jadwal Pelajaran - ' . $guru->user->name;

        $pdf = Pdf::loadView('pdf.jadwal', compact('hariList', 'title', 'semuaJadwal'));
        return $pdf->stream('jadwal-pelajaran.pdf');
    }
}
