<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreBarangRequest;
use App\Http\Requests\Admin\UpdateBarangRequest;
use App\Models\Barang;
use App\Models\Ruang;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index(Request $request)
    {
        $query = Barang::with('ruang');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('kode', 'like', "%{$search}%")
                    ->orWhere('merek', 'like', "%{$search}%");
            });
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        if ($request->filled('kondisi')) {
            $query->where('kondisi', $request->kondisi);
        }

        if ($request->filled('ruang_id')) {
            $query->where('ruang_id', $request->ruang_id);
        }

        $semuaBarang = $query->latest()->paginate(10)->withQueryString();
        $daftarRuang = Ruang::orderBy('nama')->get(['id', 'nama', 'kode']);
        $daftarKategori = Barang::select('kategori')->distinct()->pluck('kategori');

        return view('admin.barang', compact('semuaBarang', 'daftarRuang', 'daftarKategori'));
    }

    public function show(Barang $barang)
    {
        $barang->load('ruang');

        return response()->json($barang);
    }

    public function store(StoreBarangRequest $request)
    {
        $validated = $request->validated();

        $barang = Barang::create($validated);

        activity()->causedBy($request->user())->performedOn($barang)->event('created')->log('Menambahkan barang '.$validated['nama']);

        return redirect()->route('admin.barang')->with('success', 'Barang berhasil ditambahkan.');
    }

    public function update(UpdateBarangRequest $request, Barang $barang)
    {
        $validated = $request->validated();

        $barang->update($validated);

        activity()->causedBy($request->user())->performedOn($barang)->event('updated')->log('Memperbarui barang '.$validated['nama']);

        return redirect()->route('admin.barang')->with('success', 'Barang berhasil diperbarui.');
    }

    public function destroy(Request $request, Barang $barang)
    {
        $name = $barang->nama;
        $barang->delete();

        activity()->causedBy($request->user())->performedOn($barang)->event('deleted')->log('Menghapus barang '.$name);

        return redirect()->route('admin.barang')->with('success', 'Barang berhasil dihapus.');
    }
}
