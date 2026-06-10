<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\ImportSiswaJob;
use App\Models\Kelas;
use Illuminate\Http\Request;

class ImportController extends Controller
{
    public function siswa()
    {
        return view('admin.import-siswa', ['preview' => null]);
    }

    public function previewSiswa(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        $file = $request->file('file');
        $path = $file->getRealPath();

        $rows = [];
        $header = null;

        if (($handle = fopen($path, 'r')) !== false) {
            while (($data = fgetcsv($handle)) !== false) {
                if (! $header) {
                    $header = array_map('trim', $data);

                    continue;
                }
                if (count($header) === count($data)) {
                    $rows[] = array_combine($header, array_map('trim', $data));
                }
            }
            fclose($handle);
        }

        if (empty($rows)) {
            return back()->with('error', 'File CSV kosong atau tidak valid. Pastikan baris pertama adalah header: nama,nisn,nis,email,kelas,status');
        }

        $required = ['nama', 'nisn'];
        $errors = [];
        $validRows = [];

        foreach ($rows as $i => $row) {
            $rowErrors = [];
            $rowNum = $i + 1;

            foreach ($required as $field) {
                if (empty($row[$field])) {
                    $rowErrors[] = "Kolom '$field' wajib diisi";
                }
            }

            if (! empty($row['nisn']) && ! preg_match('/^\d{10,}$/', $row['nisn'])) {
                $rowErrors[] = 'NISN harus berupa angka minimal 10 digit';
            }

            if (! empty($row['email']) && ! filter_var($row['email'], FILTER_VALIDATE_EMAIL)) {
                $rowErrors[] = 'Format email tidak valid';
            }

            if (! empty($row['kelas'])) {
                $kelas = Kelas::where('nama', $row['kelas'])->first();
                if (! $kelas) {
                    $rowErrors[] = "Kelas '{$row['kelas']}' tidak ditemukan";
                }
            }

            if (! empty($row['status']) && ! in_array($row['status'], ['aktif', 'nonaktif', 'lulus', 'keluar'])) {
                $rowErrors[] = 'Status harus: aktif, nonaktif, lulus, atau keluar';
            }

            $errors[$rowNum] = $rowErrors;
            $validRows[] = $row;
        }

        $validCount = count(array_filter($errors, fn ($e) => empty($e)));
        $errorCount = count(array_filter($errors, fn ($e) => ! empty($e)));

        $request->session()->flash('import_rows', $validRows);

        return view('admin.import-siswa', compact('rows', 'errors', 'validCount', 'errorCount', 'required'))
            ->with('preview', true);
    }

    public function importSiswa(Request $request)
    {
        $rows = $request->session()->get('import_rows', []);

        if (empty($rows)) {
            return redirect()->route('admin.import.siswa')->with('error', 'Tidak ada data untuk diimport. Silakan unggah ulang file CSV.');
        }

        ImportSiswaJob::dispatch($rows);

        $request->session()->forget('import_rows');

        return redirect()->route('admin.data-siswa')->with('success', 'Import siswa sedang diproses. '.count($rows).' data ditemukan.');
    }
}
