<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BerkasPendaftaran;
use App\Models\Kelas;
use App\Models\Pendaftaran;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PpdbController extends Controller
{
    public function index(Request $request)
    {
        $query = Pendaftaran::with('berkas');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('no_pendaftaran', 'like', "%{$search}%")
                    ->orWhere('nisn', 'like', "%{$search}%")
                    ->orWhere('asal_sekolah', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('jurusan')) {
            $query->where('jurusan_daftar', $request->jurusan);
        }

        $semuaPendaftaran = $query->latest()->paginate(10)->withQueryString();

        $countMenunggu = Pendaftaran::where('status', 'menunggu')->count();
        $countDiverifikasi = Pendaftaran::where('status', 'diverifikasi')->count();
        $countDiterima = Pendaftaran::where('status', 'diterima')->count();
        $countDitolak = Pendaftaran::where('status', 'ditolak')->count();

        return view('admin.ppdb', compact(
            'semuaPendaftaran',
            'countMenunggu', 'countDiverifikasi', 'countDiterima', 'countDitolak'
        ));
    }

    public function show(Pendaftaran $pendaftaran)
    {
        $pendaftaran->load('berkas');

        return response()->json($pendaftaran);
    }

    public function verifikasi(Pendaftaran $pendaftaran)
    {
        $pendaftaran->load('berkas');

        $kelasList = Kelas::orderBy('tingkat')->orderBy('nama')->get();

        return view('admin.ppdb-verifikasi', compact('pendaftaran', 'kelasList'));
    }

    public function updateStatus(Request $request, Pendaftaran $pendaftaran)
    {
        $validated = $request->validate([
            'status' => ['required', 'string', 'in:menunggu,diverifikasi,diterima,ditolak'],
            'catatan' => ['nullable', 'string'],
        ]);

        $pendaftaran->update($validated);

        $pesan = 'Status pendaftaran berhasil diperbarui.';
        $redirectRoute = 'admin.ppdb.verifikasi';
        $redirectParams = $pendaftaran;

        if ($validated['status'] === 'diterima') {
            try {
                $created = $this->createSiswaFromPendaftaran($pendaftaran);
                if ($created) {
                    $pesan .= ' Data siswa berhasil dibuat.';
                    $redirectRoute = 'admin.ppdb';
                    $redirectParams = [];
                } else {
                    $pesan .= ' Data siswa sudah ada (NISN sudah terdaftar).';
                }
            } catch (\Exception $e) {
                Log::error('Gagal membuat siswa dari PPDB: '.$e->getMessage(), [
                    'pendaftaran_id' => $pendaftaran->id,
                    'trace' => $e->getTraceAsString(),
                ]);
                $pesan .= ' Gagal membuat data siswa: '.$e->getMessage();
            }
        }

        activity()->causedBy($request->user())->performedOn($pendaftaran)->event('updated')
            ->log('Memperbarui status PPDB '.$pendaftaran->no_pendaftaran.' menjadi '.$validated['status']);

        return redirect()->route($redirectRoute, $redirectParams)
            ->with('success', $pesan);
    }

    private function createSiswaFromPendaftaran(Pendaftaran $pendaftaran): bool
    {
        if (Siswa::where('nisn', $pendaftaran->nisn)->exists()) {
            return false;
        }

        $user = User::create([
            'name' => $pendaftaran->nama_lengkap,
            'email' => $pendaftaran->email ?? strtolower(str_replace(' ', '', $pendaftaran->nama_lengkap)).'.'.substr($pendaftaran->nisn, -4).'@siswa.eduportal.test',
            'password' => Hash::make('siswa123'),
            'role' => 'siswa',
        ]);

        $kelas = Kelas::where('tingkat', 'X')
            ->whereHas('jurusanRel', fn($q) => $q->where('nama', $pendaftaran->jurusan_daftar))
            ->first();

        $siswa = Siswa::create([
            'user_id' => $user->id,
            'nisn' => $pendaftaran->nisn,
            'nis' => null,
            'kelas_id' => $kelas?->id,
            'status' => 'aktif',
            'nama_ayah' => $pendaftaran->nama_ayah,
            'nama_ibu' => $pendaftaran->nama_ibu,
            'no_wa_ayah' => $pendaftaran->no_hp_ayah,
            'no_wa_ibu' => $pendaftaran->no_hp_ibu,
        ]);

        $pendaftaran->load('berkas');
        foreach ($pendaftaran->berkas as $berkas) {
            if (! Storage::disk('public')->exists($berkas->file_path)) {
                continue;
            }

            $ext = pathinfo($berkas->original_name ?? $berkas->file_path, PATHINFO_EXTENSION);
            $newPath = 'siswa-berkas/'.$siswa->id.'/'.$berkas->jenis.($ext ? '.'.$ext : '');

            if (! Storage::disk('public')->move($berkas->file_path, $newPath)) {
                continue;
            }

            $siswa->berkas()->create([
                'jenis' => $berkas->jenis,
                'file_path' => $newPath,
                'original_name' => $berkas->original_name,
                'mime_type' => $ext ? Storage::disk('public')->mimeType($newPath) : null,
            ]);

            if ($berkas->jenis === 'foto' && in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                $photoExt = strtolower($ext);
                $photoPath = 'profile-photos/'.$user->id.'.'.$photoExt;
                Storage::disk('public')->copy($newPath, $photoPath);
                if ($user->profile_photo_path) {
                    Storage::disk('public')->delete($user->profile_photo_path);
                }
                $user->update(['profile_photo_path' => $photoPath]);
            }
        }

        $pendaftaran->delete();

        return true;
    }

    public function downloadBerkas(BerkasPendaftaran $berkas)
    {
        if (! Storage::disk('public')->exists($berkas->file_path)) {
            abort(404);
        }

        return Storage::disk('public')->download($berkas->file_path, $berkas->original_name);
    }

    public function destroy(Request $request, Pendaftaran $pendaftaran)
    {
        foreach ($pendaftaran->berkas as $berkas) {
            Storage::disk('public')->delete($berkas->file_path);
        }
        $pendaftaran->delete();

        activity()->causedBy($request->user())->performedOn($pendaftaran)->event('deleted')
            ->log('Menghapus pendaftaran '.$pendaftaran->no_pendaftaran);

        return redirect()->route('admin.ppdb')->with('success', 'Data pendaftaran berhasil dihapus.');
    }
}
