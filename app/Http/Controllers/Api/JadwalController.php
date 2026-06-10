<?php

namespace App\Http\Controllers\Api;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Kelas;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JadwalController extends Controller
{
    public function index(): JsonResponse
    {
        $user = Auth::user();

        $query = Jadwal::with(['mapel', 'kelas', 'guru.user']);

        if ($user->role === UserRole::Siswa) {
            $siswa = $user->siswa;
            if ($siswa && $siswa->kelas_id) {
                $query->where('kelas_id', $siswa->kelas_id);
            } else {
                return response()->json(['data' => []]);
            }
        }

        return response()->json($query->latest()->paginate(20));
    }

    public function byKelas(Kelas $kelas): JsonResponse
    {
        $user = Auth::user();

        if ($user->role === UserRole::Siswa) {
            $siswa = $user->siswa;
            if (! $siswa || $siswa->kelas_id !== $kelas->id) {
                abort(403);
            }
        }

        $jadwal = Jadwal::with(['mapel', 'guru.user'])
            ->where('kelas_id', $kelas->id)
            ->orderBy('hari')
            ->orderBy('jam_mulai')
            ->get();

        return response()->json($jadwal);
    }

    public function store(Request $request): JsonResponse
    {
        $this->authorizeWrite();

        $validated = $request->validate([
            'mapel_id' => ['required', 'exists:mapel,id'],
            'kelas_id' => ['required', 'exists:kelas,id'],
            'guru_id' => ['required', 'exists:guru,id'],
            'hari' => ['required', 'string', 'in:senin,selasa,rabu,kamis,jumat,sabtu,minggu'],
            'jam_mulai' => ['required', 'date_format:H:i'],
            'jam_selesai' => ['required', 'date_format:H:i', 'after:jam_mulai'],
        ]);

        $jadwal = Jadwal::create($validated);
        $jadwal->load(['mapel', 'kelas', 'guru.user']);

        return response()->json($jadwal, 201);
    }

    public function update(Request $request, Jadwal $jadwal): JsonResponse
    {
        $this->authorizeWrite();

        $validated = $request->validate([
            'mapel_id' => ['sometimes', 'exists:mapel,id'],
            'kelas_id' => ['sometimes', 'exists:kelas,id'],
            'guru_id' => ['sometimes', 'exists:guru,id'],
            'hari' => ['sometimes', 'string', 'in:senin,selasa,rabu,kamis,jumat,sabtu,minggu'],
            'jam_mulai' => ['sometimes', 'date_format:H:i'],
            'jam_selesai' => ['sometimes', 'date_format:H:i', 'after:jam_mulai'],
        ]);

        $jadwal->update($validated);
        $jadwal->load(['mapel', 'kelas', 'guru.user']);

        return response()->json($jadwal);
    }

    public function destroy(Jadwal $jadwal): JsonResponse
    {
        $this->authorizeWrite();

        $jadwal->delete();

        return response()->json(['message' => 'Jadwal berhasil dihapus.']);
    }

    private function authorizeWrite(): void
    {
        $user = Auth::user();
        if ($user->role !== UserRole::Admin && $user->role !== UserRole::Guru) {
            abort(403, 'Unauthorized.');
        }
    }
}
