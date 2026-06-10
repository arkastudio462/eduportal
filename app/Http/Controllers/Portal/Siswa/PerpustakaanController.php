<?php

namespace App\Http\Controllers\Portal\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Peminjaman;
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

        $peminjaman = [];
        if (auth()->user()->siswa) {
            $peminjaman = Peminjaman::with('buku')
                ->where('siswa_id', auth()->user()->siswa->id)
                ->latest('tanggal_pinjam')
                ->get();
        }

        return view('portal-siswa.perpustakaan', compact('buku', 'search', 'kategori', 'kategoriList', 'peminjaman'));
    }
}
