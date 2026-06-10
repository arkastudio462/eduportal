<?php

namespace App\Http\Controllers\Portal\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Ekskul;
use App\Models\EkskulAnggota;
use App\Models\Siswa;
use Illuminate\Support\Facades\Auth;

class EkskulController extends Controller
{
    public function index()
    {
        $ekskuls = Ekskul::withCount('anggota')->where('is_active', true)->get();
        $siswa = Siswa::where('user_id', Auth::user()->id)->first();

        $myEkskuls = collect();
        if ($siswa) {
            $myEkskuls = $siswa->ekskuls()->withPivot('status')->get();
        }

        return view('portal-siswa.ekskul', compact('ekskuls', 'myEkskuls', 'siswa'));
    }

    public function join(Ekskul $ekskul)
    {
        $siswa = Siswa::where('user_id', Auth::user()->id)->first();

        if (! $siswa) {
            return back()->with('error', 'Data siswa tidak ditemukan.');
        }

        $exists = EkskulAnggota::where('ekskul_id', $ekskul->id)
            ->where('siswa_id', $siswa->id)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Anda sudah terdaftar di ekskul ini.');
        }

        if ($ekskul->anggota()->count() >= $ekskul->kuota) {
            return back()->with('error', 'Kuota ekskul sudah penuh.');
        }

        EkskulAnggota::create([
            'ekskul_id' => $ekskul->id,
            'siswa_id' => $siswa->id,
            'status' => 'aktif',
        ]);

        return back()->with('success', 'Berhasil mendaftar ekskul.');
    }

    public function leave(Ekskul $ekskul)
    {
        $siswa = Siswa::where('user_id', Auth::user()->id)->first();

        if (! $siswa) {
            return back()->with('error', 'Data siswa tidak ditemukan.');
        }

        EkskulAnggota::where('ekskul_id', $ekskul->id)
            ->where('siswa_id', $siswa->id)
            ->delete();

        return back()->with('success', 'Berhasil keluar dari ekskul.');
    }
}
