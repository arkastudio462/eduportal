<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Prestasi;
use App\Models\Setting;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PengaturanController extends Controller
{
    public function index(Request $request)
    {
        return view('admin.pengaturan', [
            'user' => $request->user(),
        ]);
    }

    public function website()
    {
        $settings = Setting::pluck('value', 'key')->toArray();
        $totalSiswa = Siswa::count();
        $totalGuru = User::where('role', 'guru')->count();
        $totalPrestasi = Prestasi::count();

        return view('admin.pengaturan-website', compact('settings', 'totalSiswa', 'totalGuru', 'totalPrestasi'));
    }

    public function websiteStore(Request $request)
    {
        $textFields = [
            'nama_sekolah', 'alamat', 'telepon', 'email_sekolah',
            'website', 'kepala_sekolah', 'visi', 'misi', 'tentang',
            'akreditasi', 'tahun_ajaran',
            'home_hero_title', 'home_hero_subtitle',
            'home_stats_siswa_label', 'home_stats_guru_label',
            'home_stats_ekskul_label', 'home_stats_prestasi_label',
            'profil_hero_title', 'profil_hero_subtitle',
            'akademik_hero_title', 'akademik_hero_subtitle',
            'kontak_alamat', 'kontak_telepon', 'kontak_email',
            'kontak_maps_url',
            'kontak_jam_senin_kamis', 'kontak_jam_jumat', 'kontak_jam_sabtu',
            'berita_hero_title',
        ];

        $imageFields = [
            'logo_sekolah', 'favicon',
            'home_hero_image', 'profil_hero_image', 'akademik_hero_image',
        ];

        foreach ($textFields as $field) {
            Setting::updateOrCreate(
                ['key' => $field],
                ['value' => $request->input($field)]
            );
        }

        foreach ($imageFields as $field) {
            if ($request->hasFile($field)) {
                $current = Setting::where('key', $field)->value('value');
                if ($current) {
                    $oldPath = str_replace('/storage/', '', $current);
                    Storage::disk('public')->delete($oldPath);
                }
                $path = $request->file($field)->store('website', 'public');
                Setting::updateOrCreate(
                    ['key' => $field],
                    ['value' => Storage::url($path)]
                );
            }
        }

        flushSettings();

        return redirect()->route('admin.pengaturan-website')
            ->with('success', 'Pengaturan website berhasil disimpan.');
    }
}
