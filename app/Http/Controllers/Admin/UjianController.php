<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUjianRequest;
use App\Http\Requests\Admin\UpdateUjianRequest;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Soal;
use App\Models\Ujian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class UjianController extends Controller
{
    public function index(Request $request)
    {
        $query = Ujian::with(['mapel', 'kelas']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('mapel')) {
            $query->where('mapel_id', $request->mapel);
        }

        if ($request->filled('search')) {
            $query->where('nama', 'like', "%{$request->search}%");
        }

        $semuaUjian = $query->latest()->paginate(10)->withQueryString();
        $daftarMapel = Cache::remember('daftar_mapel', 86400, fn () => Mapel::select('id', 'nama')->orderBy('nama')->get());
        $daftarKelas = Cache::remember('daftar_kelas', 86400, fn () => Kelas::all());
        $daftarSoal = Cache::remember('daftar_soal_mapel', 86400, fn () => Soal::with('mapel')->get());

        $totalUjian = Ujian::count();
        $sedangBerlangsung = Ujian::where('status', 'sedang_berlangsung')->count();
        $akanDatang = Ujian::where('status', 'akan_datang')->count();
        $selesai = Ujian::where('status', 'selesai')->count();

        return view('admin.ujian-online', compact(
            'semuaUjian', 'daftarMapel', 'daftarKelas', 'daftarSoal',
            'totalUjian', 'sedangBerlangsung', 'akanDatang', 'selesai'
        ));
    }

    public function show(Ujian $ujian)
    {
        $ujian->load(['mapel', 'kelas']);

        return response()->json($ujian);
    }

    public function store(StoreUjianRequest $request)
    {
        $validated = $request->validated();

        $ujian = Ujian::create($validated);
        $ujian->kelas()->attach($validated['kelas_ids']);

        activity()->causedBy($request->user())->performedOn($ujian)->event('created')->log('Menambahkan ujian '.$ujian->nama);

        return redirect()->route('admin.ujian-online')->with('status', 'Ujian berhasil ditambahkan.');
    }

    public function update(UpdateUjianRequest $request, Ujian $ujian)
    {
        $validated = $request->validated();

        $ujian->update($validated);
        $ujian->kelas()->sync($validated['kelas_ids']);

        activity()->causedBy($request->user())->performedOn($ujian)->event('updated')->log('Memperbarui ujian '.$ujian->nama);

        return redirect()->route('admin.ujian-online')->with('status', 'Ujian berhasil diperbarui.');
    }

    public function destroy(Request $request, Ujian $ujian)
    {
        $nama = $ujian->nama;
        $ujian->kelas()->detach();
        $ujian->delete();

        activity()->causedBy($request->user())->performedOn($ujian)->event('deleted')->log('Menghapus ujian '.$nama);

        return redirect()->route('admin.ujian-online')->with('status', 'Ujian berhasil dihapus.');
    }
}
