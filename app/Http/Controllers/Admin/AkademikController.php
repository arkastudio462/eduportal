<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\Mapel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\ValidationException;

class AkademikController extends Controller
{
    public function show(Jadwal $jadwal)
    {
        $jadwal->load(['kelas', 'mapel', 'guru.user']);

        return response()->json($jadwal);
    }

    public function index()
    {
        $semuaJadwal = Jadwal::with(['kelas', 'mapel', 'guru.user'])->latest()->get();
        $semuaKelas = Cache::remember('daftar_kelas', 86400, fn () => Kelas::orderBy('tingkat')->orderBy('nama')->get());
        $semuaMapel = Cache::remember('daftar_mapel', 86400, fn () => Mapel::select('id', 'nama')->orderBy('nama')->get());
        $semuaGuru = Guru::with('user')->get();
        $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', "Jum'at"];

        return view('admin.akademik', compact('semuaJadwal', 'semuaKelas', 'semuaMapel', 'semuaGuru', 'hariList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'mapel_id' => 'required|exists:mapel,id',
            'guru_id' => 'required|exists:guru,id',
            'hari' => 'required|string',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
            'ruang' => 'nullable|string|max:50',
        ]);

        $this->checkJadwalConflict($validated);

        $jadwal = Jadwal::create($validated);

        activity()->causedBy($request->user())->performedOn($jadwal)->event('created')->log('Menambahkan jadwal');

        return redirect()->route('admin.akademik')->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function update(Request $request, Jadwal $jadwal)
    {
        $validated = $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'mapel_id' => 'required|exists:mapel,id',
            'guru_id' => 'required|exists:guru,id',
            'hari' => 'required|string',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
            'ruang' => 'nullable|string|max:50',
        ]);

        $this->checkJadwalConflict($validated, $jadwal->id);

        $jadwal->update($validated);

        activity()->causedBy($request->user())->performedOn($jadwal)->event('updated')->log('Memperbarui jadwal');

        return redirect()->route('admin.akademik')->with('success', 'Jadwal berhasil diperbarui.');
    }

    private function checkJadwalConflict(array $data, ?int $ignoreId = null): void
    {
        $query = Jadwal::where('hari', $data['hari'])
            ->where(function ($q) use ($data) {
                $q->where(function ($sq) use ($data) {
                    $sq->where('jam_mulai', '<', $data['jam_selesai'])
                        ->where('jam_selesai', '>', $data['jam_mulai']);
                });
            });

        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        $conflict = $query->where(function ($q) use ($data) {
            $q->where('ruang', $data['ruang'])
                ->orWhere('guru_id', $data['guru_id'])
                ->orWhere('kelas_id', $data['kelas_id']);
        })->exists();

        if ($conflict) {
            throw ValidationException::withMessages([
                'jadwal' => 'Konflik jadwal terdeteksi: ruang, guru, atau kelas sudah memiliki jadwal di hari dan jam yang sama.',
            ]);
        }
    }

    public function destroy(Request $request, Jadwal $jadwal)
    {
        $jadwal->delete();

        activity()->causedBy($request->user())->performedOn($jadwal)->event('deleted')->log('Menghapus jadwal');

        return redirect()->route('admin.akademik')->with('success', 'Jadwal berhasil dihapus.');
    }
}
