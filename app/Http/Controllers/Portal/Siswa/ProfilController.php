<?php

namespace App\Http\Controllers\Portal\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use Illuminate\Http\Request;

class ProfilController extends Controller
{
    public function index(Request $request)
    {
        $siswa = Siswa::with('kelas.jurusanRel', 'user')
            ->where('user_id', $request->user()->id)
            ->first();

        return view('portal-siswa.profil', [
            'user' => $request->user(),
            'siswa' => $siswa,
        ]);
    }
}
