<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreMaintenanceAsetRequest;
use App\Http\Requests\Admin\UpdateMaintenanceAsetRequest;
use App\Models\Barang;
use App\Models\MaintenanceAset;
use App\Models\Ruang;
use Illuminate\Http\Request;

class MaintenanceAsetController extends Controller
{
    public function index(Request $request)
    {
        $query = MaintenanceAset::with(['barang', 'ruang']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('deskripsi', 'like', "%{$search}%")
                ->orWhere('pelaksana', 'like', "%{$search}%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        $semuaMaintenance = $query->latest()->paginate(10)->withQueryString();
        $daftarRuang = Ruang::orderBy('nama')->get(['id', 'nama', 'kode']);
        $daftarBarang = Barang::orderBy('nama')->get(['id', 'nama', 'kode']);

        $countDirencanakan = MaintenanceAset::where('status', 'direncanakan')->count();
        $countSedangDikerjakan = MaintenanceAset::where('status', 'sedang_dikerjakan')->count();
        $countSelesai = MaintenanceAset::where('status', 'selesai')->count();

        return view('admin.maintenance-aset', compact(
            'semuaMaintenance', 'daftarRuang', 'daftarBarang',
            'countDirencanakan', 'countSedangDikerjakan', 'countSelesai'
        ));
    }

    public function show(MaintenanceAset $maintenanceAset)
    {
        $maintenanceAset->load(['barang', 'ruang']);

        return response()->json($maintenanceAset);
    }

    public function store(StoreMaintenanceAsetRequest $request)
    {
        $validated = $request->validated();

        $maintenance = MaintenanceAset::create($validated);

        activity()->causedBy($request->user())->performedOn($maintenance)->event('created')->log('Menambahkan maintenance aset');

        return redirect()->route('admin.maintenance-aset')->with('success', 'Maintenance berhasil ditambahkan.');
    }

    public function update(UpdateMaintenanceAsetRequest $request, MaintenanceAset $maintenanceAset)
    {
        $validated = $request->validated();

        $maintenanceAset->update($validated);

        activity()->causedBy($request->user())->performedOn($maintenanceAset)->event('updated')->log('Memperbarui maintenance aset');

        return redirect()->route('admin.maintenance-aset')->with('success', 'Maintenance berhasil diperbarui.');
    }

    public function destroy(Request $request, MaintenanceAset $maintenanceAset)
    {
        $maintenanceAset->delete();

        activity()->causedBy($request->user())->performedOn($maintenanceAset)->event('deleted')->log('Menghapus maintenance aset');

        return redirect()->route('admin.maintenance-aset')->with('success', 'Maintenance berhasil dihapus.');
    }
}
