<?php

namespace App\Http\Controllers\Ujian;

use App\Http\Controllers\Controller;
use App\Models\Nilai;
use App\Models\Siswa;
use App\Models\Soal;
use App\Models\Ujian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BerlangsungController extends Controller
{
    public function index(Request $request, ?Ujian $ujian = null)
    {
        $soal = collect();
        $totalSoal = 0;

        if ($ujian) {
            $ujian->load('mapel');
            $soal = Soal::where('mapel_id', $ujian->mapel_id)
                ->inRandomOrder()
                ->get()
                ->map(function ($item) {
                    $item->opsi_array = is_string($item->opsi) ? json_decode($item->opsi, true) : $item->opsi;

                    return $item;
                });
            $totalSoal = $soal->count();
        }

        return view('ujian.sedang-berlangsung', compact('ujian', 'soal', 'totalSoal'));
    }

    public function submit(Request $request, Ujian $ujian)
    {
        $soal = Soal::where('mapel_id', $ujian->mapel_id)->get()->keyBy('id');

        $jawaban = $request->input('jawaban', []);
        $benar = 0;
        $salah = 0;
        $tidakDijawab = 0;
        $jawabanDetail = [];

        foreach ($soal as $s) {
            $jawabanUser = $jawaban[$s->id] ?? null;

            if (! $jawabanUser) {
                $tidakDijawab++;
                $jawabanDetail[] = [
                    'soal_id' => $s->id,
                    'soal' => $s->konten,
                    'gambar' => $s->gambar,
                    'jawaban_user' => null,
                    'jawaban_benar' => $s->jawaban,
                    'status' => 'tidak_dijawab',
                ];
            } elseif (strtolower(trim($jawabanUser)) === strtolower(trim($s->jawaban))) {
                $benar++;
                $jawabanDetail[] = [
                    'soal_id' => $s->id,
                    'soal' => $s->konten,
                    'gambar' => $s->gambar,
                    'jawaban_user' => $jawabanUser,
                    'jawaban_benar' => $s->jawaban,
                    'status' => 'benar',
                ];
            } else {
                $salah++;
                $jawabanDetail[] = [
                    'soal_id' => $s->id,
                    'soal' => $s->konten,
                    'gambar' => $s->gambar,
                    'jawaban_user' => $jawabanUser,
                    'jawaban_benar' => $s->jawaban,
                    'status' => 'salah',
                ];
            }
        }

        $totalSoal = $soal->count();
        $skor = $totalSoal > 0 ? round($benar / $totalSoal * 100, 2) : 0;

        $siswa = Siswa::where('user_id', Auth::id())->first();

        if ($siswa) {
            Nilai::updateOrCreate(
                ['siswa_id' => $siswa->id, 'ujian_id' => $ujian->id],
                [
                    'mapel_id' => $ujian->mapel_id,
                    'skor' => $skor,
                    'benar' => $benar,
                    'salah' => $salah,
                    'tidak_dijawab' => $tidakDijawab,
                    'jawaban_detail' => $jawabanDetail,
                ]
            );
        }

        return redirect()->route('ujian.hasil', $ujian->slug);
    }
}
