<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreSiswaRequest;
use App\Http\Requests\Admin\UpdateSiswaRequest;
use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $query = Siswa::with(['user', 'kelas.jurusanRel']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', fn ($u) => $u->where('name', 'like', "%{$search}%"))
                    ->orWhere('nisn', 'like', "%{$search}%");
            });
        }

        if ($request->filled('kelas')) {
            $query->whereHas('kelas', fn ($q) => $q->where('tingkat', $request->kelas));
        }

        if ($request->filled('jurusan')) {
            $query->whereHas('kelas', fn ($q) => $q->where('jurusan_id', $request->jurusan));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $trashed = $request->boolean('trashed');
        if ($trashed) {
            $query->onlyTrashed();
        }

        $semuaSiswa = $query->paginate(10)->withQueryString();
        $daftarKelas = Cache::remember('daftar_tingkat_kelas', 86400, fn () => Kelas::select('tingkat')->distinct()->pluck('tingkat'));
        $daftarJurusan = Cache::remember('daftar_jurusan_pluck', 86400, fn () => Jurusan::pluck('nama', 'id'));
        $semuaKelas = Kelas::with('jurusanRel')->orderBy('tingkat')->orderBy('nama')->get();

        return view('admin.data-siswa', compact('semuaSiswa', 'daftarKelas', 'daftarJurusan', 'semuaKelas', 'trashed'));
    }

    public function show(Siswa $siswa)
    {
        $siswa->load(['user', 'kelas.jurusanRel']);

        return response()->json($siswa);
    }

    public function store(StoreSiswaRequest $request)
    {
        $validated = $request->validated();

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
            'status' => $validated['status'],
        ]);

        activity()->causedBy($request->user())->performedOn($siswa)->event('created')->log('Menambahkan siswa '.$validated['name']);

        return redirect()->route('admin.data-siswa')->with('success', 'Siswa berhasil ditambahkan.');
    }

    public function update(UpdateSiswaRequest $request, Siswa $siswa)
    {
        $validated = $request->validated();

        $userData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
        ];
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($validated['password']);
        }
        $siswa->user->update($userData);

        $siswa->update([
            'nisn' => $validated['nisn'],
            'nis' => $validated['nis'] ?? $siswa->nis,
            'kelas_id' => $validated['kelas_id'] ?? $siswa->kelas_id,
            'status' => $validated['status'],
        ]);

        activity()->causedBy($request->user())->performedOn($siswa)->event('updated')->log('Memperbarui data siswa '.$validated['name']);

        return redirect()->route('admin.data-siswa')->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy(Request $request, Siswa $siswa)
    {
        $name = $siswa->user->name;
        $siswa->user->delete();
        $siswa->delete();

        activity()->causedBy($request->user())->performedOn($siswa)->event('deleted')->log('Menghapus siswa '.$name);

        return redirect()->route('admin.data-siswa')->with('success', 'Siswa berhasil dihapus.');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids', []);
        if (is_string($ids)) {
            $ids = json_decode($ids, true) ?? [];
        }
        $siswaList = Siswa::with('user')->whereIn('id', $ids)->get();
        foreach ($siswaList as $siswa) {
            $siswa->user->delete();
            $siswa->delete();
        }

        activity()->causedBy($request->user())->event('deleted')->log('Menghapus '.count($ids).' siswa');

        return redirect()->route('admin.data-siswa')->with('success', count($ids).' siswa berhasil dihapus.');
    }

    public function bulkRestore(Request $request)
    {
        $ids = $request->input('ids', []);
        if (is_string($ids)) {
            $ids = json_decode($ids, true) ?? [];
        }
        Siswa::onlyTrashed()->whereIn('id', $ids)->restore();

        activity()->causedBy($request->user())->event('restored')->log('Memulihkan '.count($ids).' siswa');

        return redirect()->route('admin.data-siswa', ['trashed' => 1])->with('success', count($ids).' siswa berhasil dipulihkan.');
    }
}
