<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreBeritaRequest;
use App\Http\Requests\Admin\UpdateBeritaRequest;
use App\Models\Berita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BeritaController extends Controller
{
    public function index(Request $request)
    {
        $query = Berita::latest();

        $trashed = $request->boolean('trashed');
        if ($trashed) {
            $query->onlyTrashed();
        }

        $semuaBerita = $query->paginate(10);

        return view('admin.berita', compact('semuaBerita', 'trashed'));
    }

    public function store(StoreBeritaRequest $request)
    {
        $validated = $request->validated();
        $validated['is_utama'] = $request->boolean('is_utama');

        if ($request->hasFile('gambar')) {
            $validated['gambar'] = Storage::url($request->file('gambar')->store('berita', 'public'));
        }

        Berita::create($validated);

        return redirect()->route('admin.berita')->with('success', 'Berita berhasil ditambahkan.');
    }

    public function update(UpdateBeritaRequest $request, Berita $berita)
    {
        $validated = $request->validated();
        $validated['is_utama'] = $request->boolean('is_utama');

        if ($request->hasFile('gambar')) {
            if ($berita->gambar) {
                $oldPath = str_replace('/storage/', '', $berita->gambar);
                Storage::disk('public')->delete($oldPath);
            }
            $validated['gambar'] = Storage::url($request->file('gambar')->store('berita', 'public'));
        }

        $berita->update($validated);

        return redirect()->route('admin.berita')->with('success', 'Berita berhasil diperbarui.');
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'file' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ]);

        $path = $request->file('file')->store('berita', 'public');

        return response()->json(['url' => Storage::url($path)]);
    }

    public function destroy(Berita $berita)
    {
        $berita->delete();

        return redirect()->route('admin.berita')->with('success', 'Berita berhasil dihapus.');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids', []);
        if (is_string($ids)) {
            $ids = json_decode($ids, true) ?? [];
        }
        Berita::whereIn('id', $ids)->delete();

        return redirect()->route('admin.berita')->with('success', count($ids).' berita berhasil dihapus.');
    }

    public function bulkRestore(Request $request)
    {
        $ids = $request->input('ids', []);
        if (is_string($ids)) {
            $ids = json_decode($ids, true) ?? [];
        }
        Berita::onlyTrashed()->whereIn('id', $ids)->restore();

        return redirect()->route('admin.berita', ['trashed' => 1])->with('success', count($ids).' berita berhasil dipulihkan.');
    }
}
