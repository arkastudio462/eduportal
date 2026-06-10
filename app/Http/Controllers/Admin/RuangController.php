<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreRuangRequest;
use App\Http\Requests\Admin\UpdateRuangRequest;
use App\Models\Ruang;
use Illuminate\Http\Request;

class RuangController extends Controller
{
    public function index(Request $request)
    {
        $query = Ruang::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('kode', 'like', "%{$search}%")
                    ->orWhere('gedung', 'like', "%{$search}%");
            });
        }

        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $semuaRuang = $query->latest()->paginate(10)->withQueryString();

        return view('admin.ruang', compact('semuaRuang'));
    }

    public function show(Ruang $ruang)
    {
        return response()->json($ruang);
    }

    public function store(StoreRuangRequest $request)
    {
        $validated = $request->validated();

        $ruang = Ruang::create($validated);

        activity()->causedBy($request->user())->performedOn($ruang)->event('created')->log('Menambahkan ruang '.$validated['nama']);

        return redirect()->route('admin.ruang')->with('success', 'Ruang berhasil ditambahkan.');
    }

    public function update(UpdateRuangRequest $request, Ruang $ruang)
    {
        $validated = $request->validated();

        $ruang->update($validated);

        activity()->causedBy($request->user())->performedOn($ruang)->event('updated')->log('Memperbarui ruang '.$validated['nama']);

        return redirect()->route('admin.ruang')->with('success', 'Ruang berhasil diperbarui.');
    }

    public function destroy(Request $request, Ruang $ruang)
    {
        $name = $ruang->nama;
        $ruang->delete();

        activity()->causedBy($request->user())->performedOn($ruang)->event('deleted')->log('Menghapus ruang '.$name);

        return redirect()->route('admin.ruang')->with('success', 'Ruang berhasil dihapus.');
    }
}
