<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\TracerStudy;
use Illuminate\Http\Request;

class AlumniController extends Controller
{
    public function index()
    {
        $totalAlumni = Siswa::where('status', 'alumni')->count();
        $tracerStudies = TracerStudy::latest()->take(6)->get();
        $totalTracer = TracerStudy::count();

        return view('alumni.index', compact('totalAlumni', 'tracerStudies', 'totalTracer'));
    }

    public function tracerStore(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'tahun_lulus' => 'required|integer|min:2000|max:'.date('Y'),
            'pekerjaan' => 'nullable|string|max:255',
            'universitas' => 'nullable|string|max:255',
            'kontak' => 'nullable|string|max:255',
            'pesan' => 'nullable|string|max:1000',
        ]);

        TracerStudy::create($validated);

        return redirect()->route('alumni')->with('success', 'Data tracer study berhasil dikirim! Terima kasih telah berpartisipasi.');
    }
}
