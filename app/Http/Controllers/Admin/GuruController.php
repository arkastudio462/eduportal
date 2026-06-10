<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreGuruRequest;
use App\Http\Requests\Admin\UpdateGuruRequest;
use App\Models\Guru;
use App\Models\Mapel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class GuruController extends Controller
{
    public function show(Guru $guru)
    {
        $guru->load('user');

        return response()->json($guru);
    }

    public function index(Request $request)
    {
        $query = Guru::with('user');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', fn ($u) => $u->where('name', 'like', "%{$search}%"))
                    ->orWhere('nuptk', 'like', "%{$search}%");
            });
        }

        $trashed = $request->boolean('trashed');
        if ($trashed) {
            $query->onlyTrashed();
        }

        $semuaGuru = $query->latest()->paginate(10)->withQueryString();
        $daftarMapel = Cache::remember('daftar_mapel', 86400, fn () => Mapel::select('id', 'nama')->orderBy('nama')->get());

        return view('admin.guru', compact('semuaGuru', 'daftarMapel', 'trashed'));
    }

    public function store(StoreGuruRequest $request)
    {
        $validated = $request->validated();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'guru',
        ]);

        $guru = Guru::create([
            'user_id' => $user->id,
            'nuptk' => $validated['nuptk'],
            'nip' => $validated['nip'] ?? null,
            'mata_pelajaran' => $validated['mata_pelajaran'],
        ]);

        activity()->causedBy($request->user())->performedOn($guru)->event('created')->log('Menambahkan guru '.$validated['name']);

        return redirect()->route('admin.guru')->with('success', 'Guru berhasil ditambahkan.');
    }

    public function update(UpdateGuruRequest $request, Guru $guru)
    {
        $validated = $request->validated();

        $userData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
        ];
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($validated['password']);
        }
        $guru->user->update($userData);

        $guru->update([
            'nuptk' => $validated['nuptk'],
            'nip' => $validated['nip'] ?? $guru->nip,
            'mata_pelajaran' => $validated['mata_pelajaran'],
        ]);

        activity()->causedBy($request->user())->performedOn($guru)->event('updated')->log('Memperbarui data guru '.$validated['name']);

        return redirect()->route('admin.guru')->with('success', 'Data guru berhasil diperbarui.');
    }

    public function destroy(Request $request, Guru $guru)
    {
        $name = $guru->user->name;
        $guru->user->delete();
        $guru->delete();

        activity()->causedBy($request->user())->performedOn($guru)->event('deleted')->log('Menghapus guru '.$name);

        return redirect()->route('admin.guru')->with('success', 'Guru berhasil dihapus.');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids', []);
        if (is_string($ids)) {
            $ids = json_decode($ids, true) ?? [];
        }
        $guruList = Guru::with('user')->whereIn('id', $ids)->get();
        foreach ($guruList as $guru) {
            $guru->user->delete();
            $guru->delete();
        }

        activity()->causedBy($request->user())->event('deleted')->log('Menghapus '.count($ids).' guru');

        return redirect()->route('admin.guru')->with('success', count($ids).' guru berhasil dihapus.');
    }

    public function bulkRestore(Request $request)
    {
        $ids = $request->input('ids', []);
        if (is_string($ids)) {
            $ids = json_decode($ids, true) ?? [];
        }
        Guru::onlyTrashed()->whereIn('id', $ids)->restore();

        activity()->causedBy($request->user())->event('restored')->log('Memulihkan '.count($ids).' guru');

        return redirect()->route('admin.guru', ['trashed' => 1])->with('success', count($ids).' guru berhasil dipulihkan.');
    }
}
