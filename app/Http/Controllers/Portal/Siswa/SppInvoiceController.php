<?php

namespace App\Http\Controllers\Portal\Siswa;

use App\Http\Controllers\Controller;
use App\Models\PembayaranSpp;
use App\Models\Siswa;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class SppInvoiceController extends Controller
{
    public function download(PembayaranSpp $pembayaran)
    {
        $siswa = Siswa::where('user_id', Auth::user()->id)->first();

        if (! $siswa || $pembayaran->siswa_id !== $siswa->id) {
            abort(403);
        }

        $pembayaran->load('siswa.user', 'siswa.kelas');

        $pdf = Pdf::loadView('pdf.invoice-spp', compact('pembayaran'));

        $namaBulan = Carbon::create()->month((int) $pembayaran->bulan)->locale('id')->monthName;

        return $pdf->stream("invoice-spp-{$namaBulan}-{$pembayaran->tahun}.pdf");
    }
}
