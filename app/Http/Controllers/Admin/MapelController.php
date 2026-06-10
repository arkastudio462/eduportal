<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mapel;
use Illuminate\Http\Request;

class MapelController extends Controller
{
    public function show(Mapel $mapel)
    {
        return response()->json($mapel);
    }

    public function index()
    {
        $semuaMapel = Mapel::latest()->get();

        return view('admin.mapel', compact('semuaMapel'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'kode' => 'nullable|string|max:50',
        ]);

        Mapel::create($validated);

        return redirect()->route('admin.mapel')->with('success', 'Mata pelajaran berhasil ditambahkan.');
    }

    public function update(Request $request, Mapel $mapel)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'kode' => 'nullable|string|max:50',
        ]);

        $mapel->update($validated);

        return redirect()->route('admin.mapel')->with('success', 'Mata pelajaran berhasil diperbarui.');
    }

    public function destroy(Mapel $mapel)
    {
        $mapel->delete();

        return redirect()->route('admin.mapel')->with('success', 'Mata pelajaran berhasil dihapus.');
    }
}
