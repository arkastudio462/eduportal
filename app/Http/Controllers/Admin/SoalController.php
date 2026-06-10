<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreSoalRequest;
use App\Http\Requests\Admin\UpdateSoalRequest;
use App\Models\Mapel;
use App\Models\Soal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class SoalController extends Controller
{
    public function index(Request $request)
    {
        $query = Soal::with('mapel');

        if ($request->filled('mapel')) {
            $query->where('mapel_id', $request->mapel);
        }

        if ($request->filled('tipe')) {
            $query->where('tipe', $request->tipe);
        }

        if ($request->filled('kesulitan')) {
            $query->where('kesulitan', $request->kesulitan);
        }

        if ($request->filled('search')) {
            $query->where('konten', 'like', "%{$request->search}%");
        }

        $trashed = $request->boolean('trashed');
        if ($trashed) {
            $query->onlyTrashed();
        }

        $semuaSoal = $query->latest()->paginate(10)->withQueryString();
        $daftarMapel = Cache::remember('daftar_mapel', 86400, fn () => Mapel::select('id', 'nama')->orderBy('nama')->get());

        $totalSoal = Soal::withTrashed()->count();
        $totalPg = Soal::withTrashed()->where('tipe', 'PG')->count();
        $totalEssay = Soal::withTrashed()->where('tipe', 'Essay')->count();
        $totalGandaKompleks = Soal::withTrashed()->where('tipe', 'Ganda Kompleks')->count();

        return view('admin.bank-soal', compact(
            'semuaSoal', 'daftarMapel',
            'totalSoal', 'totalPg', 'totalEssay', 'totalGandaKompleks', 'trashed'
        ));
    }

    public function show(Soal $soal)
    {
        $soal->load('mapel');

        return response()->json($soal);
    }

    public function store(StoreSoalRequest $request)
    {
        $validated = $request->validated();

        if ($request->hasFile('gambar')) {
            $validated['gambar'] = Storage::url($request->file('gambar')->store('soal-images', 'public'));
        }

        $soal = Soal::create($validated);

        activity()->causedBy($request->user())->performedOn($soal)->event('created')->log('Menambahkan soal');

        return redirect()->route('admin.bank-soal')->with('status', 'Soal berhasil ditambahkan.');
    }

    public function update(UpdateSoalRequest $request, Soal $soal)
    {
        $validated = $request->validated();

        if ($request->hasFile('gambar')) {
            if ($soal->gambar) {
                $oldPath = str_replace('/storage/', '', $soal->gambar);
                Storage::disk('public')->delete($oldPath);
            }
            $validated['gambar'] = Storage::url($request->file('gambar')->store('soal-images', 'public'));
        }

        $soal->update($validated);

        activity()->causedBy($request->user())->performedOn($soal)->event('updated')->log('Memperbarui soal');

        return redirect()->route('admin.bank-soal')->with('status', 'Soal berhasil diperbarui.');
    }

    public function destroy(Request $request, Soal $soal)
    {
        if ($soal->gambar) {
            $oldPath = str_replace('/storage/', '', $soal->gambar);
            Storage::disk('public')->delete($oldPath);
        }
        $soal->delete();

        activity()->causedBy($request->user())->performedOn($soal)->event('deleted')->log('Menghapus soal');

        return redirect()->route('admin.bank-soal')->with('status', 'Soal berhasil dihapus.');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids', []);
        if (is_string($ids)) {
            $ids = json_decode($ids, true) ?? [];
        }
        Soal::whereIn('id', $ids)->delete();

        return redirect()->route('admin.bank-soal')->with('status', count($ids).' soal berhasil dihapus.');
    }

    public function bulkRestore(Request $request)
    {
        $ids = $request->input('ids', []);
        if (is_string($ids)) {
            $ids = json_decode($ids, true) ?? [];
        }
        Soal::onlyTrashed()->whereIn('id', $ids)->restore();

        return redirect()->route('admin.bank-soal', ['trashed' => 1])->with('status', count($ids).' soal berhasil dipulihkan.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:json,txt|max:2048',
        ]);

        $content = file_get_contents($request->file('file')->path());
        $data = json_decode($content, true);

        if (! $data || ! is_array($data)) {
            return redirect()->route('admin.bank-soal')->with('error', 'Format file JSON tidak valid.');
        }

        $imported = 0;
        $errors = [];

        foreach ($data as $i => $item) {
            $tipe = $item['tipe'] ?? '';
            $kesulitan = $item['kesulitan'] ?? 'Sedang';
            $konten = $item['konten'] ?? '';
            $jawaban = $item['jawaban'] ?? '';
            $opsi = $item['opsi'] ?? null;
            $mapel_id = $item['mapel_id'] ?? $request->input('mapel_id');

            if (! in_array($tipe, ['PG', 'Essay', 'Ganda Kompleks'])) {
                $errors[] = 'Baris '.($i + 1).': Tipe soal tidak valid.';

                continue;
            }

            if (empty($konten) || empty($jawaban)) {
                $errors[] = 'Baris '.($i + 1).': Konten dan jawaban wajib diisi.';

                continue;
            }

            Soal::create([
                'mapel_id' => $mapel_id,
                'tipe' => $tipe,
                'kesulitan' => $kesulitan,
                'konten' => $konten,
                'jawaban' => $jawaban,
                'opsi' => $tipe !== 'Essay' ? $opsi : null,
            ]);

            $imported++;
        }

        $message = "Berhasil mengimport {$imported} soal.";

        if (! empty($errors)) {
            $message .= ' '.implode('; ', $errors);
        }

        return redirect()->route('admin.bank-soal')->with('status', $message);
    }
}
