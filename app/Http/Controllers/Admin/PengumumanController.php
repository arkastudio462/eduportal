<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman;
use App\Models\User;
use App\Notifications\PengumumanNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class PengumumanController extends Controller
{
    public function show(Pengumuman $pengumuman)
    {
        return response()->json($pengumuman);
    }

    public function index()
    {
        $semuaPengumuman = Pengumuman::latest()->get();

        return view('admin.pengumuman', compact('semuaPengumuman'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'konten' => 'required|string',
            'tipe' => 'required|string|max:50',
        ]);

        $validated['tanggal'] = now();

        $pengumuman = Pengumuman::create($validated);

        User::chunkById(100, function ($users) use ($validated, $pengumuman) {
            Notification::send($users, new PengumumanNotification(
                $validated['judul'],
                $validated['konten'],
                $pengumuman->id,
            ));
        });

        return redirect()->route('admin.pengumuman')->with('success', 'Pengumuman berhasil dibuat.');
    }

    public function update(Request $request, Pengumuman $pengumuman)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'konten' => 'required|string',
            'tipe' => 'required|string|max:50',
        ]);

        $pengumuman->update($validated);

        return redirect()->route('admin.pengumuman')->with('success', 'Pengumuman berhasil diperbarui.');
    }

    public function destroy(Pengumuman $pengumuman)
    {
        $pengumuman->delete();

        return redirect()->route('admin.pengumuman')->with('success', 'Pengumuman berhasil dihapus.');
    }
}
