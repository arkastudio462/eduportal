<?php

namespace App\Http\Controllers\Portal\Guru;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use Illuminate\Http\Request;

class PerpustakaanController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $kategori = $request->input('kategori');

        $buku = Buku::query()
            ->when($search, fn ($q) => $q->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                    ->orWhere('penulis', 'like', "%{$search}%")
                    ->orWhere('penerbit', 'like', "%{$search}%");
            }))
            ->when($kategori, fn ($q) => $q->where('kategori', $kategori))
            ->latest()
            ->paginate(12);

        $kategoriList = Buku::select('kategori')->distinct()->whereNotNull('kategori')->pluck('kategori');

        return view('portal-guru.perpustakaan', compact('buku', 'search', 'kategori', 'kategoriList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'penulis' => 'nullable|string|max:255',
            'penerbit' => 'nullable|string|max:255',
            'kategori' => 'nullable|string|max:100',
            'tahun_terbit' => 'nullable|integer|min:1900|max:2099',
            'isbn' => 'nullable|string|max:30',
            'deskripsi' => 'nullable|string',
            'stok' => 'nullable|integer|min:0',
        ]);

        Buku::create($validated);

        return redirect()->route('portal-guru.perpustakaan')->with('success', 'Buku berhasil ditambahkan.');
    }

    public function update(Request $request, Buku $buku)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'penulis' => 'nullable|string|max:255',
            'penerbit' => 'nullable|string|max:255',
            'kategori' => 'nullable|string|max:100',
            'tahun_terbit' => 'nullable|integer|min:1900|max:2099',
            'isbn' => 'nullable|string|max:30',
            'deskripsi' => 'nullable|string',
            'stok' => 'nullable|integer|min:0',
        ]);

        $buku->update($validated);

        return redirect()->route('portal-guru.perpustakaan')->with('success', 'Buku berhasil diperbarui.');
    }

    public function destroy(Buku $buku)
    {
        $buku->delete();

        return redirect()->route('portal-guru.perpustakaan')->with('success', 'Buku berhasil dihapus.');
    }
}
