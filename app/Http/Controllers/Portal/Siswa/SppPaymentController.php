<?php

namespace App\Http\Controllers\Portal\Siswa;

use App\Http\Controllers\Controller;
use App\Models\PembayaranSpp;
use App\Services\MidtransService;
use Illuminate\Support\Facades\Auth;

class SppPaymentController extends Controller
{
    public function pay(PembayaranSpp $pembayaran)
    {
        $siswa = Auth::user()->siswa;

        if (! $siswa || $pembayaran->siswa_id !== $siswa->id) {
            abort(403);
        }

        if ($pembayaran->status === 'lunas') {
            return redirect()->route('portal-siswa.spp')->with('info', 'SPP ini sudah lunas.');
        }

        try {
            $midtrans = new MidtransService;
            $midtrans->createSnapTransaction($pembayaran);

            return response()->json([
                'snap_token' => $pembayaran->snap_token,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal memproses pembayaran: '.$e->getMessage()], 500);
        }
    }
}
