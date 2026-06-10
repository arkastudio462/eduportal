<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Prestasi;
use Illuminate\Http\Request;

class PrestasiController extends Controller
{
    public function show(Prestasi $prestasi)
    {
        return response()->json($prestasi);
    }

    public function index()
    {
        $semuaPrestasi = Prestasi::latest()->get();

        return view('admin.prestasi', compact('semuaPrestasi'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tingkat' => 'nullable|string|max:50',
            'peringkat' => 'nullable|string|max:50',
            'tanggal' => 'nullable|date',
            'tipe' => 'required|string|in:akademik,non-akademik',
        ]);

        Prestasi::create($validated);

        return redirect()->route('admin.prestasi')->with('success', 'Prestasi berhasil ditambahkan.');
    }

    public function update(Request $request, Prestasi $prestasi)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tingkat' => 'nullable|string|max:50',
            'peringkat' => 'nullable|string|max:50',
            'tanggal' => 'nullable|date',
            'tipe' => 'required|string|in:akademik,non-akademik',
        ]);

        $prestasi->update($validated);

        return redirect()->route('admin.prestasi')->with('success', 'Prestasi berhasil diperbarui.');
    }

    public function destroy(Prestasi $prestasi)
    {
        $prestasi->delete();

        return redirect()->route('admin.prestasi')->with('success', 'Prestasi berhasil dihapus.');
    }
}
