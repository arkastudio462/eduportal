<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\ExportDataJob;
use App\Models\Absensi;
use App\Models\Nilai;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ExportController extends Controller
{
    public function siswa()
    {
        $siswa = Siswa::with('user', 'kelas')->orderBy('nisn')->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="data-siswa-'.date('Y-m-d').'.csv"',
        ];

        $callback = function () use ($siswa) {
            $file = fopen('php://output', 'w');
            fwrite($file, "\xEF\xBB\xBF"); // BOM for Excel
            fputcsv($file, ['No', 'NISN', 'NIS', 'Nama', 'Kelas', 'Status']);

            foreach ($siswa as $i => $s) {
                fputcsv($file, [
                    $i + 1,
                    $s->nisn,
                    $s->nis,
                    $s->user->name ?? '-',
                    $s->kelas->nama ?? '-',
                    $s->status,
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function nilai(Request $request)
    {
        $query = Nilai::with('siswa.user', 'siswa.kelas', 'mapel');

        if ($request->semester) {
            $query->where('semester', $request->semester);
        }
        if ($request->mapel_id) {
            $query->where('mapel_id', $request->mapel_id);
        }

        $nilai = $query->orderBy('siswa_id')->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="data-nilai-'.date('Y-m-d').'.csv"',
        ];

        $callback = function () use ($nilai) {
            $file = fopen('php://output', 'w');
            fwrite($file, "\xEF\xBB\xBF");
            fputcsv($file, ['No', 'NISN', 'Nama', 'Kelas', 'Mapel', 'Jenis', 'Semester', 'Skor', 'Benar', 'Salah']);

            foreach ($nilai as $i => $n) {
                fputcsv($file, [
                    $i + 1,
                    $n->siswa->nisn ?? '-',
                    $n->siswa->user->name ?? '-',
                    $n->siswa->kelas->nama ?? '-',
                    $n->mapel->nama ?? '-',
                    $n->jenis ?? 'ujian',
                    $n->semester ?? '-',
                    $n->skor,
                    $n->benar,
                    $n->salah,
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function absensi(Request $request)
    {
        $query = Absensi::with('siswa.user', 'siswa.kelas');

        if ($request->kelas_id) {
            $query->whereHas('siswa', fn ($q) => $q->where('kelas_id', $request->kelas_id));
        }
        if ($request->tanggal_mulai) {
            $query->whereDate('tanggal', '>=', $request->tanggal_mulai);
        }
        if ($request->tanggal_selesai) {
            $query->whereDate('tanggal', '<=', $request->tanggal_selesai);
        }

        $absensi = $query->orderBy('tanggal', 'desc')->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="data-absensi-'.date('Y-m-d').'.csv"',
        ];

        $callback = function () use ($absensi) {
            $file = fopen('php://output', 'w');
            fwrite($file, "\xEF\xBB\xBF");
            fputcsv($file, ['No', 'NISN', 'Nama', 'Kelas', 'Tanggal', 'Status', 'Keterangan']);

            foreach ($absensi as $i => $a) {
                fputcsv($file, [
                    $i + 1,
                    $a->siswa->nisn ?? '-',
                    $a->siswa->user->name ?? '-',
                    $a->siswa->kelas->nama ?? '-',
                    $a->tanggal->format('Y-m-d'),
                    $a->status,
                    $a->keterangan ?? '',
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function siswaAsync(Request $request)
    {
        ExportDataJob::dispatch('siswa', $request->only(['kelas_id', 'status']), $request->user()->id);

        return redirect()->back()->with('success', 'Export data siswa sedang diproses. Anda akan mendapatkan notifikasi setelah selesai.');
    }

    public function nilaiAsync(Request $request)
    {
        ExportDataJob::dispatch('nilai', $request->only(['mapel_id', 'semester']), $request->user()->id);

        return redirect()->back()->with('success', 'Export data nilai sedang diproses. Anda akan mendapatkan notifikasi setelah selesai.');
    }

    public function absensiAsync(Request $request)
    {
        ExportDataJob::dispatch('absensi', $request->only(['kelas_id', 'tanggal_mulai', 'tanggal_selesai', 'status']), $request->user()->id);

        return redirect()->back()->with('success', 'Export data absensi sedang diproses. Anda akan mendapatkan notifikasi setelah selesai.');
    }

    public function download($path)
    {
        return Storage::download($path);
    }
}
