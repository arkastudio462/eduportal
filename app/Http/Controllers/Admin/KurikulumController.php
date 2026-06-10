<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KiKd;
use App\Models\Mapel;
use App\Models\Promes;
use App\Models\Prota;
use App\Models\Silabus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KurikulumController extends Controller
{
    public function index()
    {
        $daftarMapel = Mapel::orderBy('nama')->get();
        $kiKd = KiKd::with('mapel')->latest()->get()->groupBy(fn ($item) => $item->mapel?->nama ?? 'Lainnya');
        $silabus = Silabus::with('mapel')->latest()->get()->groupBy(fn ($item) => $item->mapel?->nama ?? 'Lainnya');
        $prota = Prota::with('mapel')->latest()->get()->groupBy(fn ($item) => $item->mapel?->nama ?? 'Lainnya');

        return view('admin.kurikulum', compact('daftarMapel', 'kiKd', 'silabus', 'prota'));
    }

    // === KI/KD ===
    public function storeKiKd(Request $request)
    {
        $validated = $request->validate([
            'mapel_id' => 'required|exists:mapel,id',
            'kode' => 'required|string|max:50',
            'tipe' => 'required|in:KI,KD',
            'deskripsi' => 'required|string',
            'semester' => 'nullable|string',
        ]);

        KiKd::create($validated);

        return redirect()->route('admin.kurikulum')->with('success', 'KI/KD berhasil ditambahkan.');
    }

    public function updateKiKd(Request $request, KiKd $kiKd)
    {
        $validated = $request->validate([
            'kode' => 'required|string|max:50',
            'tipe' => 'required|in:KI,KD',
            'deskripsi' => 'required|string',
            'semester' => 'nullable|string',
        ]);

        $kiKd->update($validated);

        return redirect()->route('admin.kurikulum')->with('success', 'KI/KD berhasil diperbarui.');
    }

    public function destroyKiKd(KiKd $kiKd)
    {
        $kiKd->delete();

        return redirect()->route('admin.kurikulum')->with('success', 'KI/KD berhasil dihapus.');
    }

    // === Silabus ===
    public function storeSilabus(Request $request)
    {
        $validated = $request->validate([
            'mapel_id' => 'required|exists:mapel,id',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'semester' => 'nullable|string',
        ]);

        if ($request->hasFile('file')) {
            $validated['file'] = Storage::url($request->file('file')->store('silabus', 'public'));
        }

        Silabus::create($validated);

        return redirect()->route('admin.kurikulum')->with('success', 'Silabus berhasil ditambahkan.');
    }

    public function updateSilabus(Request $request, Silabus $silabus)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'semester' => 'nullable|string',
        ]);

        if ($request->hasFile('file')) {
            if ($silabus->file) {
                $oldPath = str_replace('/storage/', '', $silabus->file);
                Storage::disk('public')->delete($oldPath);
            }
            $validated['file'] = Storage::url($request->file('file')->store('silabus', 'public'));
        }

        $silabus->update($validated);

        return redirect()->route('admin.kurikulum')->with('success', 'Silabus berhasil diperbarui.');
    }

    public function destroySilabus(Silabus $silabus)
    {
        if ($silabus->file) {
            $oldPath = str_replace('/storage/', '', $silabus->file);
            Storage::disk('public')->delete($oldPath);
        }
        $silabus->delete();

        return redirect()->route('admin.kurikulum')->with('success', 'Silabus berhasil dihapus.');
    }

    // === Prota ===
    public function storeProta(Request $request)
    {
        $validated = $request->validate([
            'mapel_id' => 'required|exists:mapel,id',
            'tahun_ajaran' => 'required|string|max:50',
            'deskripsi' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
        ]);

        if ($request->hasFile('file')) {
            $validated['file'] = Storage::url($request->file('file')->store('prota', 'public'));
        }

        $prota = Prota::create($validated);

        // Handle promes items
        if ($request->filled('bulan')) {
            foreach ($request->bulan as $i => $bulan) {
                if (! empty($bulan)) {
                    Promes::create([
                        'prota_id' => $prota->id,
                        'bulan' => $bulan,
                        'materi' => $request->materi[$i] ?? null,
                        'minggu_ke' => $request->minggu_ke[$i] ?? null,
                    ]);
                }
            }
        }

        return redirect()->route('admin.kurikulum')->with('success', 'Prota berhasil ditambahkan.');
    }

    public function destroyProta(Prota $prota)
    {
        if ($prota->file) {
            $oldPath = str_replace('/storage/', '', $prota->file);
            Storage::disk('public')->delete($oldPath);
        }
        $prota->delete();

        return redirect()->route('admin.kurikulum')->with('success', 'Prota berhasil dihapus.');
    }

    public function showProta(Prota $prota)
    {
        $prota->load('mapel', 'promes');

        return response()->json($prota);
    }
}
