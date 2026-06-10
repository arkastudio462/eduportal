<?php

namespace App\Http\Controllers\Api;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\Nilai;
use App\Models\Siswa;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NilaiController extends Controller
{
    public function index(): JsonResponse
    {
        $user = Auth::user();

        $query = Nilai::with(['siswa.user', 'ujian.mapel']);

        if ($user->role === UserRole::Siswa) {
            $siswa = $user->siswa;
            if (! $siswa) {
                return response()->json(['data' => []]);
            }
            $query->where('siswa_id', $siswa->id);
        }

        return response()->json($query->latest()->paginate(20));
    }

    public function bySiswa(Siswa $siswa): JsonResponse
    {
        $user = Auth::user();

        if ($user->role === UserRole::Siswa) {
            $mySiswa = $user->siswa;
            if (! $mySiswa || $mySiswa->id !== $siswa->id) {
                abort(403);
            }
        }

        $nilai = Nilai::with('ujian.mapel')
            ->where('siswa_id', $siswa->id)
            ->latest()
            ->get();

        return response()->json($nilai);
    }

    public function store(Request $request): JsonResponse
    {
        $this->authorizeWrite();

        $validated = $request->validate([
            'siswa_id' => ['required', 'exists:siswa,id'],
            'ujian_id' => ['required', 'exists:ujian,id'],
            'skor' => ['required', 'numeric', 'min:0', 'max:100'],
        ]);

        $nilai = Nilai::create($validated);
        $nilai->load(['siswa.user', 'ujian.mapel']);

        return response()->json($nilai, 201);
    }

    public function update(Request $request, Nilai $nilai): JsonResponse
    {
        $this->authorizeWrite();

        $validated = $request->validate([
            'skor' => ['sometimes', 'numeric', 'min:0', 'max:100'],
            'ujian_id' => ['sometimes', 'exists:ujian,id'],
        ]);

        $nilai->update($validated);
        $nilai->load(['siswa.user', 'ujian.mapel']);

        return response()->json($nilai);
    }

    public function destroy(Nilai $nilai): JsonResponse
    {
        $this->authorizeWrite();

        $nilai->delete();

        return response()->json(['message' => 'Nilai berhasil dihapus.']);
    }

    private function authorizeWrite(): void
    {
        $user = Auth::user();
        if ($user->role !== UserRole::Admin && $user->role !== UserRole::Guru) {
            abort(403, 'Unauthorized.');
        }
    }
}
