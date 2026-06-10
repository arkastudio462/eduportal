<?php

namespace App\Http\Controllers\Portal\Guru;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\PresensiGuru;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PresensiGuruController extends Controller
{
    public function index()
    {
        $guru = Guru::where('user_id', Auth::id())->first();
        $riwayat = collect();

        if ($guru) {
            $riwayat = PresensiGuru::where('guru_id', $guru->id)
                ->latest()
                ->paginate(20);
        }

        return view('portal-guru.presensi-guru', compact('guru', 'riwayat'));
    }

    public function scan(Request $request)
    {
        $request->validate([
            'qr_token' => 'required|string',
        ]);

        $guru = Guru::where('user_id', Auth::id())->firstOrFail();

        $globalToken = Setting::where('key', 'presensi_guru_qr_token')->value('value');

        if (! $globalToken || $request->qr_token !== $globalToken) {
            return redirect()->route('portal-guru.presensi-guru')->with('error', 'Token QR Code tidak valid.');
        }

        return $this->processPresensi($guru);
    }

    public function scanByToken(string $token)
    {
        $guru = Guru::where('user_id', Auth::id())->firstOrFail();

        $globalToken = Setting::where('key', 'presensi_guru_qr_token')->value('value');

        if (! $globalToken || $token !== $globalToken) {
            return redirect()->route('portal-guru.presensi-guru')->with('error', 'Token QR Code tidak valid.');
        }

        return $this->processPresensi($guru);
    }

    private function processPresensi(Guru $guru)
    {
        $today = now()->format('Y-m-d');

        $presensi = PresensiGuru::where('guru_id', $guru->id)
            ->where('tanggal', $today)
            ->first();

        if (! $presensi) {
            PresensiGuru::create([
                'guru_id' => $guru->id,
                'tanggal' => $today,
                'check_in' => now()->format('H:i'),
                'status' => 'hadir',
            ]);

            return redirect()->route('portal-guru.presensi-guru')->with('success', 'Check-in berhasil. Selamat datang!');
        }

        if ($presensi->check_in && ! $presensi->check_out) {
            $presensi->update(['check_out' => now()->format('H:i')]);

            return redirect()->route('portal-guru.presensi-guru')->with('success', 'Check-out berhasil. Sampai jumpa!');
        }

        return redirect()->route('portal-guru.presensi-guru')->with('error', 'Anda sudah melakukan check-in dan check-out hari ini.');
    }
}
