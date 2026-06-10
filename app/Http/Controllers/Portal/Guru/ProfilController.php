<?php

namespace App\Http\Controllers\Portal\Guru;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use Illuminate\Http\Request;

class ProfilController extends Controller
{
    public function index(Request $request)
    {
        $guru = Guru::where('user_id', $request->user()->id)->first();

        return view('portal-guru.profil', [
            'user' => $request->user(),
            'guru' => $guru,
        ]);
    }
}
