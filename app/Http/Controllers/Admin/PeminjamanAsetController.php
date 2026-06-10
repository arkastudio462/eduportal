<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePeminjamanAsetRequest;
use App\Models\Barang;
use App\Models\PeminjamanAset;
use App\Models\Ruang;
use Illuminate\Http\Request;

class PeminjamanAsetController extends Controller
{
    public function index(Request $request)
    {
        $query = PeminjamanAset::with(['ruang', 'barang', 'approver', 'peminjam']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('tujuan', 'like', "%{$search}%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $semuaPeminjaman = $query->latest()->paginate(10)->withQueryString();
        $daftarRuang = Ruang::orderBy('nama')->get(['id', 'nama', 'kode']);
        $daftarBarang = Barang::orderBy('nama')->get(['id', 'nama', 'kode']);

        return view('admin.peminjaman-aset', compact('semuaPeminjaman', 'daftarRuang', 'daftarBarang'));
    }

    public function show(PeminjamanAset $peminjamanAset)
    {
        $peminjamanAset->load(['ruang', 'barang', 'approver', 'peminjam']);

        return response()->json($peminjamanAset);
    }

    public function store(StorePeminjamanAsetRequest $request)
    {
        $validated = $request->validated();

        $validated['approved_by'] = $request->user()->id;

        $peminjaman = PeminjamanAset::create($validated);

        activity()->causedBy($request->user())->performedOn($peminjaman)->event('created')->log('Menambahkan peminjaman aset');

        return redirect()->route('admin.peminjaman-aset')->with('success', 'Peminjaman berhasil ditambahkan.');
    }

    public function update(Request $request, PeminjamanAset $peminjamanAset)
    {
        $validated = $request->validate([
            'status' => ['required', 'string', 'in:diajukan,disetujui,dipinjam,dikembalikan,ditolak'],
            'keterangan' => ['nullable', 'string'],
        ]);

        $validated['approved_by'] = $request->user()->id;

        $peminjamanAset->update($validated);

        activity()->causedBy($request->user())->performedOn($peminjamanAset)->event('updated')->log('Memperbarui status peminjaman aset');

        return redirect()->route('admin.peminjaman-aset')->with('success', 'Status peminjaman berhasil diperbarui.');
    }

    public function destroy(Request $request, PeminjamanAset $peminjamanAset)
    {
        $peminjamanAset->delete();

        activity()->causedBy($request->user())->performedOn($peminjamanAset)->event('deleted')->log('Menghapus peminjaman aset');

        return redirect()->route('admin.peminjaman-aset')->with('success', 'Peminjaman berhasil dihapus.');
    }
}
