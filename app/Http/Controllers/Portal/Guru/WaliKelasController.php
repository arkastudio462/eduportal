<?php

namespace App\Http\Controllers\Portal\Guru;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Guru;
use App\Models\PembayaranSpp;
use App\Models\Siswa;
use App\Models\User;
use App\Notifications\PengumumanNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class WaliKelasController extends Controller
{
    public function index()
    {
        $guru = Guru::with('kelasWali.siswa.user')
            ->where('user_id', Auth::user()->id)
            ->first();

        if (! $guru || $guru->kelasWali->isEmpty()) {
            return view('portal-guru.wali-kelas', [
                'kelasWali' => null,
                'siswaList' => collect(),
                'statistik' => [],
            ]);
        }

        $kelasWali = $guru->kelasWali->first();
        $siswaList = Siswa::with('user')
            ->where('kelas_id', $kelasWali->id)
            ->aktif()
            ->orderBy(User::select('name')->whereColumn('users.id', 'siswa.user_id'))
            ->get();

        $siswaIds = $siswaList->pluck('id');

        $sppLunas = PembayaranSpp::whereIn('siswa_id', $siswaIds)
            ->where('bulan', now()->format('m'))
            ->where('tahun', now()->format('Y'))
            ->where('status', 'lunas')
            ->count();

        $sppBelum = $siswaList->count() - $sppLunas;

        $kehadiranHariIni = Absensi::whereIn('siswa_id', $siswaIds)
            ->where('tanggal', now()->format('Y-m-d'))
            ->get();

        $hadir = $kehadiranHariIni->where('status', 'hadir')->count();
        $sakit = $kehadiranHariIni->where('status', 'sakit')->count();
        $izin = $kehadiranHariIni->where('status', 'izin')->count();
        $alpha = $kehadiranHariIni->where('status', 'alpha')->count();

        $statistik = [
            'total' => $siswaList->count(),
            'spp_lunas' => $sppLunas,
            'spp_belum' => $sppBelum,
            'hadir' => $hadir,
            'sakit' => $sakit,
            'izin' => $izin,
            'alpha' => $alpha,
        ];

        return view('portal-guru.wali-kelas', compact('kelasWali', 'siswaList', 'statistik'));
    }

    public function sendMessage(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'pesan' => 'required|string',
        ]);

        $guru = Guru::with('kelasWali.siswa.user')
            ->where('user_id', Auth::user()->id)
            ->first();

        if (! $guru || $guru->kelasWali->isEmpty()) {
            return back()->with('error', 'Anda belum ditugaskan sebagai wali kelas.');
        }

        $siswaUserIds = $guru->kelasWali->flatMap->siswa->pluck('user_id');
        $users = User::whereIn('id', $siswaUserIds)->get();

        Notification::send($users, new PengumumanNotification(
            $validated['judul'],
            $validated['pesan'],
            null,
        ));

        return redirect()->route('portal-guru.wali-kelas')->with('success', 'Pesan berhasil dikirim ke '.$users->count().' siswa.');
    }
}
