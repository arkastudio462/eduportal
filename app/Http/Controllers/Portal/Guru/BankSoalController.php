<?php

namespace App\Http\Controllers\Portal\Guru;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Mapel;
use App\Models\Soal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BankSoalController extends Controller
{
    public function index(Request $request)
    {
        $guru = Guru::where('user_id', Auth::id())->first();

        if (! $guru || ! $guru->mata_pelajaran) {
            return view('portal-guru.bank-soal', [
                'semuaSoal' => collect(),
                'mapel' => null,
                'totalSoal' => 0,
                'totalPg' => 0,
                'totalEssay' => 0,
                'totalGandaKompleks' => 0,
            ]);
        }

        $mapel = Mapel::where('nama', $guru->mata_pelajaran)->first();

        if (! $mapel) {
            return view('portal-guru.bank-soal', [
                'semuaSoal' => collect(),
                'mapel' => null,
                'totalSoal' => 0,
                'totalPg' => 0,
                'totalEssay' => 0,
                'totalGandaKompleks' => 0,
            ]);
        }

        $query = Soal::with('mapel')->where('mapel_id', $mapel->id);

        if ($request->filled('tipe')) {
            $query->where('tipe', $request->tipe);
        }

        if ($request->filled('kesulitan')) {
            $query->where('kesulitan', $request->kesulitan);
        }

        if ($request->filled('search')) {
            $query->where('konten', 'like', "%{$request->search}%");
        }

        $semuaSoal = $query->latest()->paginate(10)->withQueryString();

        return view('portal-guru.bank-soal', [
            'semuaSoal' => $semuaSoal,
            'mapel' => $mapel,
            'totalSoal' => Soal::where('mapel_id', $mapel->id)->count(),
            'totalPg' => Soal::where('mapel_id', $mapel->id)->where('tipe', 'PG')->count(),
            'totalEssay' => Soal::where('mapel_id', $mapel->id)->where('tipe', 'Essay')->count(),
            'totalGandaKompleks' => Soal::where('mapel_id', $mapel->id)->where('tipe', 'Ganda Kompleks')->count(),
        ]);
    }

    public function show(Soal $soal)
    {
        $soal->load('mapel');

        return response()->json($soal);
    }

    public function store(Request $request)
    {
        $guru = Guru::where('user_id', Auth::id())->first();
        $mapel = Mapel::where('nama', $guru->mata_pelajaran ?? '')->first();

        if (! $mapel) {
            return redirect()->route('portal-guru.bank-soal')->with('error', 'Mata pelajaran tidak ditemukan.');
        }

        $validated = $request->validate([
            'tipe' => 'required|in:PG,Essay,Ganda Kompleks',
            'kesulitan' => 'required|in:Mudah,Sedang,Sulit',
            'konten' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'opsi' => 'nullable|json',
            'jawaban' => 'required|string',
        ]);

        $validated['mapel_id'] = $mapel->id;

        if ($request->hasFile('gambar')) {
            $validated['gambar'] = Storage::url($request->file('gambar')->store('soal-images', 'public'));
        }

        Soal::create($validated);

        return redirect()->route('portal-guru.bank-soal')->with('success', 'Soal berhasil ditambahkan.');
    }

    public function update(Request $request, Soal $soal)
    {
        $this->authorize('update', $soal);

        $validated = $request->validate([
            'tipe' => 'required|in:PG,Essay,Ganda Kompleks',
            'kesulitan' => 'required|in:Mudah,Sedang,Sulit',
            'konten' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'opsi' => 'nullable|json',
            'jawaban' => 'required|string',
        ]);

        if ($request->hasFile('gambar')) {
            if ($soal->gambar) {
                $oldPath = str_replace('/storage/', '', $soal->gambar);
                Storage::disk('public')->delete($oldPath);
            }
            $validated['gambar'] = Storage::url($request->file('gambar')->store('soal-images', 'public'));
        }

        $soal->update($validated);

        return redirect()->route('portal-guru.bank-soal')->with('success', 'Soal berhasil diperbarui.');
    }

    public function destroy(Soal $soal)
    {
        $this->authorize('delete', $soal);

        if ($soal->gambar) {
            $oldPath = str_replace('/storage/', '', $soal->gambar);
            Storage::disk('public')->delete($oldPath);
        }
        $soal->delete();

        return redirect()->route('portal-guru.bank-soal')->with('success', 'Soal berhasil dihapus.');
    }

    public function import(Request $request)
    {
        $guru = Guru::where('user_id', Auth::id())->first();
        $mapel = Mapel::where('nama', $guru->mata_pelajaran ?? '')->first();

        if (! $mapel) {
            return redirect()->route('portal-guru.bank-soal')->with('error', 'Mata pelajaran tidak ditemukan.');
        }

        $request->validate([
            'file' => 'required|file|mimes:json,txt|max:2048',
        ]);

        $content = file_get_contents($request->file('file')->path());
        $data = json_decode($content, true);

        if (! $data || ! is_array($data)) {
            return redirect()->route('portal-guru.bank-soal')->with('error', 'Format file JSON tidak valid.');
        }

        $imported = 0;
        $errors = [];

        foreach ($data as $i => $item) {
            $tipe = $item['tipe'] ?? '';
            $kesulitan = $item['kesulitan'] ?? 'Sedang';
            $konten = $item['konten'] ?? '';
            $jawaban = $item['jawaban'] ?? '';
            $opsi = $item['opsi'] ?? null;

            if (! in_array($tipe, ['PG', 'Essay', 'Ganda Kompleks'])) {
                $errors[] = 'Baris '.($i + 1).': Tipe soal tidak valid.';

                continue;
            }

            if (empty($konten) || empty($jawaban)) {
                $errors[] = 'Baris '.($i + 1).': Konten dan jawaban wajib diisi.';

                continue;
            }

            Soal::create([
                'mapel_id' => $mapel->id,
                'tipe' => $tipe,
                'kesulitan' => $kesulitan,
                'konten' => $konten,
                'opsi' => is_array($opsi) ? json_encode($opsi) : $opsi,
                'jawaban' => $jawaban,
            ]);

            $imported++;
        }

        $message = "Berhasil mengimpor $imported soal.";
        if (! empty($errors)) {
            $message .= ' '.implode(' ', $errors);
        }

        return redirect()->route('portal-guru.bank-soal')->with('success', $message);
    }

    public function downloadTemplate()
    {
        $template = [
            [
                'tipe' => 'PG',
                'kesulitan' => 'Mudah',
                'konten' => 'Ibukota Indonesia adalah...',
                'opsi' => [
                    ['label' => 'A', 'value' => 'Jakarta'],
                    ['label' => 'B', 'value' => 'Bandung'],
                    ['label' => 'C', 'value' => 'Surabaya'],
                    ['label' => 'D', 'value' => 'Medan'],
                ],
                'jawaban' => 'Jakarta',
            ],
            [
                'tipe' => 'Essay',
                'kesulitan' => 'Sedang',
                'konten' => 'Jelaskan penyebab terjadinya global warming!',
                'opsi' => null,
                'jawaban' => 'Pemanasan global disebabkan oleh efek rumah kaca...',
            ],
            [
                'tipe' => 'Ganda Kompleks',
                'kesulitan' => 'Sulit',
                'konten' => 'Pilihlah pernyataan yang benar tentang fotosintesis!',
                'opsi' => [
                    ['label' => 'A', 'value' => 'Membutuhkan cahaya matahari'],
                    ['label' => 'B', 'value' => 'Menghasilkan oksigen'],
                    ['label' => 'C', 'value' => 'Terjadi di mitokondria'],
                    ['label' => 'D', 'value' => 'Membutuhkan klorofil'],
                ],
                'jawaban' => 'Membutuhkan cahaya matahari,Membutuhkan klorofil',
            ],
        ];

        return response()->streamDownload(function () use ($template) {
            echo json_encode($template, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }, 'template-import-soal.json', ['Content-Type' => 'application/json']);
    }
}
