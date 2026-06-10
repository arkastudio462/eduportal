<?php

namespace App\Http\Controllers\Portal\Siswa;

use App\Http\Controllers\Controller;
use App\Models\PembayaranSpp;
use App\Services\MidtransService;
use Illuminate\Support\Facades\Auth;

class SppController extends Controller
{
    public function index()
    {
        $siswa = Auth::user()->siswa;

        $pembayaranSpp = [];
        if ($siswa) {
            $pembayaranSpp = PembayaranSpp::where('siswa_id', $siswa->id)
                ->orderBy('tahun', 'desc')
                ->orderBy('bulan', 'desc')
                ->get();
        }

        $clientKey = MidtransService::getClientKey();

        return view('portal-siswa.spp', compact('pembayaranSpp', 'clientKey'));
    }
}
