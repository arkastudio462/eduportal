<?php

namespace App\Http\Controllers\Portal\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Modul;
use App\Models\Siswa;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ModulController extends Controller
{
    public function index()
    {
        $siswa = Siswa::where('user_id', Auth::user()->id)->first();

        $moduls = collect();
        if ($siswa && $siswa->kelas_id) {
            $moduls = Modul::with('guru.user', 'mapel')
                ->where(function ($q) use ($siswa) {
                    $q->where('kelas_id', $siswa->kelas_id)
                        ->orWhereNull('kelas_id');
                })
                ->latest()
                ->get();
        }

        return view('portal-siswa.modul', compact('moduls'));
    }

    public function download(Modul $modul)
    {
        if (! Storage::disk('public')->exists($modul->file)) {
            abort(404);
        }

        return Storage::disk('public')->download($modul->file);
    }
}
