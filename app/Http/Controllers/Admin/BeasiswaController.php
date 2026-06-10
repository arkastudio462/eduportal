<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Beasiswa;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BeasiswaController extends Controller
{
    public function index(Request $request)
    {
        $query = Beasiswa::with('siswa.user');

        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }

        $semuaBeasiswa = $query->latest()->paginate(20);
        $tahunList = Beasiswa::select('tahun')->distinct()->orderBy('tahun', 'desc')->pluck('tahun');
        $kelasList = Kelas::orderBy('tingkat')->orderBy('nama')->get();

        return view('admin.beasiswa', compact('semuaBeasiswa', 'tahunList', 'kelasList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'nama_beasiswa' => 'required|string|max:255',
            'penyelenggara' => 'required|string|max:255',
            'tahun' => 'required|integer|min:2000|max:2099',
            'keterangan' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('file')) {
            $validated['file'] = Storage::url($request->file('file')->store('beasiswa', 'public'));
        }

        Beasiswa::create($validated);

        return redirect()->route('admin.beasiswa')->with('success', 'Data beasiswa berhasil ditambahkan.');
    }

    public function update(Request $request, Beasiswa $beasiswa)
    {
        $validated = $request->validate([
            'nama_beasiswa' => 'required|string|max:255',
            'penyelenggara' => 'required|string|max:255',
            'tahun' => 'required|integer|min:2000|max:2099',
            'keterangan' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('file')) {
            if ($beasiswa->file) {
                $oldPath = str_replace('/storage/', '', $beasiswa->file);
                Storage::disk('public')->delete($oldPath);
            }
            $validated['file'] = Storage::url($request->file('file')->store('beasiswa', 'public'));
        }

        $beasiswa->update($validated);

        return redirect()->route('admin.beasiswa')->with('success', 'Data beasiswa berhasil diperbarui.');
    }

    public function destroy(Beasiswa $beasiswa)
    {
        if ($beasiswa->file) {
            $oldPath = str_replace('/storage/', '', $beasiswa->file);
            Storage::disk('public')->delete($oldPath);
        }
        $beasiswa->delete();

        return redirect()->route('admin.beasiswa')->with('success', 'Data beasiswa berhasil dihapus.');
    }
}
