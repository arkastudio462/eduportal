<?php

namespace App\Http\Controllers\Portal\Guru;

use App\Http\Controllers\Controller;
use App\Http\Requests\Guru\StoreAbsensiRequest;
use App\Models\Absensi;
use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AbsensiController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $guru = Guru::where('user_id', $user->id)->first();

        $kelasIds = collect();
        $kelasList = collect();

        if ($guru) {
            $kelasIds = Jadwal::where('guru_id', $guru->id)
                ->pluck('kelas_id')->unique();
            $kelasList = Kelas::whereIn('id', $kelasIds)->orderBy('tingkat')->orderBy('nama')->get();
        }

        $selectedKelas = $request->kelas_id ? Kelas::find($request->kelas_id) : $kelasList->first();
        $tanggal = $request->tanggal ?? now()->format('Y-m-d');

        $siswaList = collect();
        $dataAbsensi = collect();

        if ($selectedKelas) {
            $siswaList = Siswa::with('user')
                ->where('kelas_id', $selectedKelas->id)
                ->aktif()
                ->orderBy(User::select('name')->whereColumn('users.id', 'siswa.user_id'))
                ->get();

            $dataAbsensi = Absensi::where('tanggal', $tanggal)
                ->whereIn('siswa_id', $siswaList->pluck('id'))
                ->get()
                ->keyBy('siswa_id');
        }

        return view('portal-guru.absensi', compact(
            'kelasList', 'siswaList', 'dataAbsensi', 'selectedKelas', 'tanggal'
        ));
    }

    public function store(StoreAbsensiRequest $request)
    {
        $validated = $request->validated();

        foreach ($validated['absensi'] as $siswaId => $data) {
            Absensi::updateOrCreate(
                ['siswa_id' => $siswaId, 'tanggal' => $validated['tanggal']],
                ['status' => $data['status'], 'keterangan' => $data['keterangan'] ?? null]
            );
        }

        return redirect()->route('portal-guru.absensi', [
            'kelas_id' => $validated['kelas_id'],
            'tanggal' => $validated['tanggal'],
        ])->with('success', 'Absensi berhasil disimpan.');
    }
}
