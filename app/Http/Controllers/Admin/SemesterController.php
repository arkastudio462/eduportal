<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KalenderAkademik;
use App\Models\Semester;
use Illuminate\Http\Request;

class SemesterController extends Controller
{
    public function index()
    {
        $semesters = Semester::latest()->get();
        $kalender = KalenderAkademik::with('semester')->latest()->get();

        return view('admin.semester', compact('semesters', 'kalender'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'tahun_ajaran' => 'required|string|max:9',
            'semester' => 'required|in:Ganjil,Genap',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
        ]);

        Semester::create($data);

        return redirect()->route('admin.semester')->with('success', 'Semester berhasil ditambahkan.');
    }

    public function update(Request $request, Semester $semester)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'tahun_ajaran' => 'required|string|max:9',
            'semester' => 'required|in:Ganjil,Genap',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
        ]);

        $semester->update($data);

        return redirect()->route('admin.semester')->with('success', 'Semester berhasil diperbarui.');
    }

    public function setActive(Semester $semester)
    {
        Semester::where('is_active', true)->update(['is_active' => false]);
        $semester->update(['is_active' => true]);

        return redirect()->route('admin.semester')->with('success', 'Semester aktif berhasil diubah.');
    }

    public function destroy(Semester $semester)
    {
        $semester->delete();

        return redirect()->route('admin.semester')->with('success', 'Semester berhasil dihapus.');
    }

    public function storeEvent(Request $request)
    {
        $data = $request->validate([
            'semester_id' => 'nullable|exists:semesters,id',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:2000',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'tipe' => 'required|in:academic,holiday,exam,registration,other',
        ]);

        KalenderAkademik::create($data);

        return redirect()->route('admin.semester')->with('success', 'Acara kalender berhasil ditambahkan.');
    }

    public function destroyEvent(KalenderAkademik $kalender)
    {
        $kalender->delete();

        return redirect()->route('admin.semester')->with('success', 'Acara kalender berhasil dihapus.');
    }
}
