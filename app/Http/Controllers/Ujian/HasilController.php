<?php

namespace App\Http\Controllers\Ujian;

use App\Http\Controllers\Controller;
use App\Models\Nilai;
use App\Models\Ujian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HasilController extends Controller
{
    public function index(Request $request, ?Ujian $ujian = null)
    {
        $nilai = null;

        if ($ujian) {
            $ujian->load('mapel');
            $nilai = Nilai::with('siswa.user')
                ->where('ujian_id', $ujian->id)
                ->whereHas('siswa', fn ($q) => $q->where('user_id', Auth::id()))
                ->first();
        }

        return view('ujian.hasil', compact('nilai', 'ujian'));
    }
}
