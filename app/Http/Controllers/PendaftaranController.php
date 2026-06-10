<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePendaftaranRequest;
use App\Models\BerkasPendaftaran;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PendaftaranController extends Controller
{
    public function index()
    {
        return view('ppdb');
    }

    public function store(StorePendaftaranRequest $request)
    {
        $validated = $request->validated();

        $noPendaftaran = 'PPDB-'.now()->format('Ymd').'-'.strtoupper(substr(uniqid(), -5));

        $pendaftaran = Pendaftaran::create([
            'no_pendaftaran' => $noPendaftaran,
            'nama_lengkap' => $validated['nama_lengkap'],
            'nisn' => $validated['nisn'],
            'tempat_lahir' => $validated['tempat_lahir'] ?? null,
            'tanggal_lahir' => $validated['tanggal_lahir'] ?? null,
            'jenis_kelamin' => $validated['jenis_kelamin'] ?? null,
            'agama' => $validated['agama'] ?? null,
            'alamat' => $validated['alamat'] ?? null,
            'no_hp' => $validated['no_hp'] ?? null,
            'email' => $validated['email'] ?? null,
            'asal_sekolah' => $validated['asal_sekolah'] ?? null,
            'jurusan_daftar' => $validated['jurusan_daftar'] ?? null,
            'nilai_rata_rata' => $validated['nilai_rata_rata'] ?? null,
            'nama_ayah' => $validated['nama_ayah'] ?? null,
            'nama_ibu' => $validated['nama_ibu'] ?? null,
            'no_hp_ayah' => $validated['no_hp_ayah'] ?? null,
            'no_hp_ibu' => $validated['no_hp_ibu'] ?? null,
            'pekerjaan_ayah' => $validated['pekerjaan_ayah'] ?? null,
            'pekerjaan_ibu' => $validated['pekerjaan_ibu'] ?? null,
            'status' => 'menunggu',
        ]);

        $jenisBerkas = ['ijazah', 'kk', 'akta', 'foto', 'skhun', 'prestasi'];
        foreach ($jenisBerkas as $jenis) {
            if ($request->hasFile("berkas.$jenis")) {
                $file = $request->file("berkas.$jenis");
                $path = $file->store('berkas-pendaftaran', 'public');
                BerkasPendaftaran::create([
                    'pendaftaran_id' => $pendaftaran->id,
                    'jenis' => $jenis,
                    'file_path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                ]);
            }
        }

        return redirect()->route('ppdb.success', $pendaftaran->no_pendaftaran);
    }

    public function success($noPendaftaran)
    {
        $pendaftaran = Pendaftaran::where('no_pendaftaran', $noPendaftaran)->firstOrFail();

        return view('ppdb-success', compact('pendaftaran'));
    }

    public function cekStatus(Request $request)
    {
        $request->validate(['no_pendaftaran' => 'required|string']);
        $pendaftaran = Pendaftaran::where('no_pendaftaran', $request->no_pendaftaran)->first();
        if (! $pendaftaran) {
            return back()->with('error', 'Nomor pendaftaran tidak ditemukan.');
        }
        $pendaftaran->load('berkas');

        return view('ppdb-status', compact('pendaftaran'));
    }

    public function downloadBerkas(BerkasPendaftaran $berkas)
    {
        if (! Storage::disk('public')->exists($berkas->file_path)) {
            abort(404);
        }

        return Storage::disk('public')->download($berkas->file_path, $berkas->original_name);
    }
}
