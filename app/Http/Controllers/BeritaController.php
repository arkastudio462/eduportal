<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use Illuminate\Http\Request;

class BeritaController extends Controller
{
    public function show($slug)
    {
        $berita = Berita::where('slug', $slug)->firstOrFail();
        $beritaPopuler = Berita::where('id', '!=', $berita->id)->latest('tanggal')->take(5)->get();
        $kategoriList = Berita::select('kategori')->distinct()->pluck('kategori');

        return view('berita-detail', compact('berita', 'beritaPopuler', 'kategoriList'));
    }

    public function index(Request $request)
    {
        $query = Berita::latest('tanggal');

        if ($request->filled('kategori') && $request->kategori !== 'semua') {
            $query->where('kategori', $request->kategori);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) {
                $q->where('judul', 'like', '%'.request('search').'%')
                    ->orWhere('konten', 'like', '%'.request('search').'%');
            });
        }

        $berita = $query->paginate(9);

        $beritaUtama = Berita::where('is_utama', true)->latest('tanggal')->first()
            ?? Berita::latest('tanggal')->first();

        $kategoriList = Berita::select('kategori')->distinct()->pluck('kategori');
        $beritaPopuler = Berita::latest('tanggal')->take(5)->get();

        return view('berita', compact('berita', 'beritaUtama', 'kategoriList', 'beritaPopuler'));
    }
}
