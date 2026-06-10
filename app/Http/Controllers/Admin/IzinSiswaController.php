<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IzinSiswa;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class IzinSiswaController extends Controller
{
    public function index(Request $request)
    {
        $query = IzinSiswa::with('siswa.user', 'disetujuiOleh');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('kelas_id')) {
            $query->whereHas('siswa', fn ($q) => $q->where('kelas_id', $request->kelas_id));
        }

        $semuaIzin = $query->latest()->paginate(20);
        $kelasList = Kelas::orderBy('tingkat')->orderBy('nama')->get();

        return view('admin.izin-siswa', compact('semuaIzin', 'kelasList'));
    }

    public function approve(IzinSiswa $izinSiswa)
    {
        $izinSiswa->update(['status' => 'disetujui', 'disetujui_oleh' => auth()->id()]);

        return redirect()->route('admin.izin-siswa')->with('success', 'Izin berhasil disetujui.');
    }

    public function reject(Request $request, IzinSiswa $izinSiswa)
    {
        $izinSiswa->update(['status' => 'ditolak', 'disetujui_oleh' => auth()->id()]);

        return redirect()->route('admin.izin-siswa')->with('success', 'Izin berhasil ditolak.');
    }

    public function destroy(IzinSiswa $izinSiswa)
    {
        if ($izinSiswa->file) {
            $oldPath = str_replace('/storage/', '', $izinSiswa->file);
            Storage::disk('public')->delete($oldPath);
        }
        $izinSiswa->delete();

        return redirect()->route('admin.izin-siswa')->with('success', 'Data izin berhasil dihapus.');
    }
}
