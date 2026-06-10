<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\PresensiGuru;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PresensiGuruController extends Controller
{
    public function index(Request $request)
    {
        $query = PresensiGuru::with('guru.user');

        if ($request->filled('tanggal')) {
            $query->where('tanggal', $request->tanggal);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $semuaPresensi = $query->latest()->paginate(20);
        $semuaGuru = Guru::with('user')->orderBy('id')->get();

        $qrToken = Setting::where('key', 'presensi_guru_qr_token')->value('value');

        return view('admin.presensi-guru', compact('semuaPresensi', 'semuaGuru', 'qrToken'));
    }

    public function update(Request $request, PresensiGuru $presensiGuru)
    {
        $validated = $request->validate([
            'check_in' => 'nullable',
            'check_out' => 'nullable',
            'status' => 'required|in:hadir,izin,sakit,alpha',
            'keterangan' => 'nullable|string',
        ]);

        $presensiGuru->update($validated);

        return redirect()->route('admin.presensi-guru')->with('success', 'Presensi guru berhasil diperbarui.');
    }

    public function generateQr()
    {
        $token = Str::random(32);
        Setting::updateOrCreate(
            ['key' => 'presensi_guru_qr_token'],
            ['value' => $token]
        );

        return redirect()->route('admin.presensi-guru')->with('success', 'QR Code berhasil digenerate.');
    }

    public function destroy(PresensiGuru $presensiGuru)
    {
        $presensiGuru->delete();

        return redirect()->route('admin.presensi-guru')->with('success', 'Presensi guru berhasil dihapus.');
    }
}
