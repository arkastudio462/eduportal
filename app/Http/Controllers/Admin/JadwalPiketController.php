<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\JadwalPiket;
use Illuminate\Http\Request;

class JadwalPiketController extends Controller
{
    public function index()
    {
        $jadwalPiket = JadwalPiket::with('guru.user')->latest()->get()->groupBy('hari');
        $semuaGuru = Guru::with('user')->orderBy('id')->get();
        $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', "Jum'at", 'Sabtu'];

        return view('admin.jadwal-piket', compact('jadwalPiket', 'semuaGuru', 'hariList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'guru_id' => 'required|exists:guru,id',
            'hari' => 'required|string',
        ]);

        $exists = JadwalPiket::where('guru_id', $validated['guru_id'])
            ->where('hari', $validated['hari'])
            ->exists();

        if ($exists) {
            return redirect()->route('admin.jadwal-piket')->with('error', 'Guru sudah memiliki jadwal piket di hari yang sama.');
        }

        JadwalPiket::create($validated);

        return redirect()->route('admin.jadwal-piket')->with('success', 'Jadwal piket berhasil ditambahkan.');
    }

    public function destroy(JadwalPiket $jadwalPiket)
    {
        $jadwalPiket->delete();

        return redirect()->route('admin.jadwal-piket')->with('success', 'Jadwal piket berhasil dihapus.');
    }
}
