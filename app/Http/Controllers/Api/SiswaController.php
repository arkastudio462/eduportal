<?php

namespace App\Http\Controllers\Api;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SiswaController extends Controller
{
    public function index(): JsonResponse
    {
        $user = Auth::user();

        $query = Siswa::with(['user', 'kelas']);

        if ($user->role === UserRole::Siswa) {
            $siswa = $user->siswa;
            if (! $siswa) {
                return response()->json(['data' => []]);
            }
            $query->where('id', $siswa->id);
        }

        return response()->json($query->latest()->paginate(20));
    }

    public function show(Siswa $siswa): JsonResponse
    {
        $user = Auth::user();

        if ($user->role === UserRole::Siswa) {
            $mySiswa = $user->siswa;
            if (! $mySiswa || $mySiswa->id !== $siswa->id) {
                abort(403);
            }
        }

        $siswa->load(['user', 'kelas', 'nilai.ujian']);

        return response()->json($siswa);
    }

    public function store(Request $request): JsonResponse
    {
        $this->authorizeWrite();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'nisn' => ['required', 'string', 'unique:siswa,nisn'],
            'nis' => ['nullable', 'string'],
            'kelas_id' => ['nullable', 'exists:kelas,id'],
            'status' => ['nullable', 'string', 'in:aktif,nonaktif,lulus'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'siswa',
        ]);

        $siswa = Siswa::create([
            'user_id' => $user->id,
            'nisn' => $validated['nisn'],
            'nis' => $validated['nis'] ?? null,
            'kelas_id' => $validated['kelas_id'] ?? null,
            'status' => $validated['status'] ?? 'aktif',
        ]);

        $siswa->load(['user', 'kelas']);

        return response()->json($siswa, 201);
    }

    public function update(Request $request, Siswa $siswa): JsonResponse
    {
        $this->authorizeWrite();

        $validated = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'email' => ['sometimes', 'email', 'unique:users,email,'.$siswa->user_id],
            'password' => ['sometimes', 'string', 'min:8'],
            'nisn' => ['sometimes', 'string', 'unique:siswa,nisn,'.$siswa->id],
            'nis' => ['nullable', 'string'],
            'kelas_id' => ['nullable', 'exists:kelas,id'],
            'status' => ['sometimes', 'string', 'in:aktif,nonaktif,lulus'],
        ]);

        if (isset($validated['name'])) {
            $siswa->user->update(['name' => $validated['name']]);
        }
        if (isset($validated['email'])) {
            $siswa->user->update(['email' => $validated['email']]);
        }
        if (isset($validated['password'])) {
            $siswa->user->update(['password' => Hash::make($validated['password'])]);
        }

        $siswa->update($validated);
        $siswa->load(['user', 'kelas']);

        return response()->json($siswa);
    }

    public function destroy(Siswa $siswa): JsonResponse
    {
        $this->authorizeWrite();

        $siswa->user->delete();
        $siswa->delete();

        return response()->json(['message' => 'Siswa berhasil dihapus.']);
    }

    private function authorizeWrite(): void
    {
        $user = Auth::user();
        if ($user->role !== UserRole::Admin && $user->role !== UserRole::Guru) {
            abort(403, 'Unauthorized. Only admin and guru can modify data.');
        }
    }
}
