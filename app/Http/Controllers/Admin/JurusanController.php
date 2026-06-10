<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jurusan;
use Illuminate\Http\Request;

class JurusanController extends Controller
{
    public function show(Jurusan $jurusan)
    {
        return response()->json($jurusan);
    }

    public function index()
    {
        $semuaJurusan = Jurusan::latest()->paginate(10);

        return view('admin.jurusan', compact('semuaJurusan'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'kode' => 'nullable|string|max:50',
        ]);

        Jurusan::create($validated);

        return redirect()->route('admin.jurusan')->with('success', 'Jurusan berhasil ditambahkan.');
    }

    public function update(Request $request, Jurusan $jurusan)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'kode' => 'nullable|string|max:50',
        ]);

        $jurusan->update($validated);

        return redirect()->route('admin.jurusan')->with('success', 'Jurusan berhasil diperbarui.');
    }

    public function destroy(Jurusan $jurusan)
    {
        $jurusan->delete();

        return redirect()->route('admin.jurusan')->with('success', 'Jurusan berhasil dihapus.');
    }
}
