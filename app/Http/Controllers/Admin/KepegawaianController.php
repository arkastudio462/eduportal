<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CutiGuru;
use App\Models\Guru;
use App\Models\Kepegawaian;
use App\Models\KinerjaGuru;
use App\Models\SertifikasiGuru;
use App\Models\TunjanganGuru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class KepegawaianController extends Controller
{
    public function index()
    {
        $semuaGuru = Guru::with('user')->orderBy('id')->get();
        $kepegawaian = Kepegawaian::with('guru.user')->latest()->get();
        $cuti = CutiGuru::with('guru.user', 'approver')->latest()->get();
        $kinerja = KinerjaGuru::with('guru.user')->latest()->get();
        $sertifikasi = SertifikasiGuru::with('guru.user')->latest()->get();
        $tunjangan = TunjanganGuru::with('guru.user')->latest()->get();

        return view('admin.kepegawaian', compact(
            'semuaGuru', 'kepegawaian', 'cuti', 'kinerja', 'sertifikasi', 'tunjangan'
        ));
    }

    // === DATA KEPEGAWAIAN ===
    public function storeKepegawaian(Request $request)
    {
        $validated = $request->validate([
            'guru_id' => 'required|exists:guru,id',
            'status_kepegawaian' => 'required|string',
            'golongan' => 'nullable|string',
            'jabatan' => 'nullable|string',
            'tmt_cpns' => 'nullable|date',
            'tmt_pns' => 'nullable|date',
            'masa_kerja_tahun' => 'nullable|integer|min:0',
            'masa_kerja_bulan' => 'nullable|integer|min:0|max:11',
            'nik' => 'nullable|string|max:20',
            'npwp' => 'nullable|string|max:30',
            'karpeg' => 'nullable|string|max:30',
        ]);

        $exists = Kepegawaian::where('guru_id', $validated['guru_id'])->exists();
        if ($exists) {
            return redirect()->route('admin.kepegawaian')->with('error', 'Data kepegawaian untuk guru ini sudah ada.');
        }

        Kepegawaian::create($validated);

        return redirect()->route('admin.kepegawaian')->with('success', 'Data kepegawaian berhasil ditambahkan.');
    }

    public function updateKepegawaian(Request $request, Kepegawaian $kepegawaian)
    {
        $validated = $request->validate([
            'status_kepegawaian' => 'required|string',
            'golongan' => 'nullable|string',
            'jabatan' => 'nullable|string',
            'tmt_cpns' => 'nullable|date',
            'tmt_pns' => 'nullable|date',
            'masa_kerja_tahun' => 'nullable|integer|min:0',
            'masa_kerja_bulan' => 'nullable|integer|min:0|max:11',
            'nik' => 'nullable|string|max:20',
            'npwp' => 'nullable|string|max:30',
            'karpeg' => 'nullable|string|max:30',
        ]);

        $kepegawaian->update($validated);

        return redirect()->route('admin.kepegawaian')->with('success', 'Data kepegawaian berhasil diperbarui.');
    }

    public function destroyKepegawaian(Kepegawaian $kepegawaian)
    {
        $kepegawaian->delete();

        return redirect()->route('admin.kepegawaian')->with('success', 'Data kepegawaian berhasil dihapus.');
    }

    // === CUTI GURU ===
    public function storeCuti(Request $request)
    {
        $validated = $request->validate([
            'guru_id' => 'required|exists:guru,id',
            'jenis_cuti' => 'required|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'alasan' => 'required|string|max:500',
            'file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('file')) {
            $validated['file'] = Storage::url($request->file('file')->store('cuti-guru', 'public'));
        }

        CutiGuru::create($validated);

        return redirect()->route('admin.kepegawaian')->with('success', 'Data cuti berhasil ditambahkan.');
    }

    public function updateCuti(Request $request, CutiGuru $cutiGuru)
    {
        $validated = $request->validate([
            'jenis_cuti' => 'required|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'alasan' => 'required|string|max:500',
            'status' => 'required|in:pending,disetujui,ditolak',
            'file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('file')) {
            if ($cutiGuru->file) {
                $oldPath = str_replace('/storage/', '', $cutiGuru->file);
                Storage::disk('public')->delete($oldPath);
            }
            $validated['file'] = Storage::url($request->file('file')->store('cuti-guru', 'public'));
        }

        if ($validated['status'] === 'disetujui' && $cutiGuru->status !== 'disetujui') {
            $validated['approved_by'] = Auth::id();
        }

        $cutiGuru->update($validated);

        return redirect()->route('admin.kepegawaian')->with('success', 'Data cuti berhasil diperbarui.');
    }

    public function destroyCuti(CutiGuru $cutiGuru)
    {
        if ($cutiGuru->file) {
            $oldPath = str_replace('/storage/', '', $cutiGuru->file);
            Storage::disk('public')->delete($oldPath);
        }
        $cutiGuru->delete();

        return redirect()->route('admin.kepegawaian')->with('success', 'Data cuti berhasil dihapus.');
    }

    // === KINERJA GURU ===
    public function storeKinerja(Request $request)
    {
        $validated = $request->validate([
            'guru_id' => 'required|exists:guru,id',
            'tahun_ajaran' => 'required|string|max:50',
            'semester' => 'required|string',
            'jam_mengajar_per_minggu' => 'nullable|integer|min:0',
            'skor_pkg' => 'nullable|numeric|min:0|max:100',
            'predikat_pkg' => 'nullable|string',
            'kategori' => 'required|string',
            'catatan' => 'nullable|string',
        ]);

        KinerjaGuru::create($validated);

        return redirect()->route('admin.kepegawaian')->with('success', 'Data kinerja berhasil ditambahkan.');
    }

    public function updateKinerja(Request $request, KinerjaGuru $kinerjaGuru)
    {
        $validated = $request->validate([
            'tahun_ajaran' => 'required|string|max:50',
            'semester' => 'required|string',
            'jam_mengajar_per_minggu' => 'nullable|integer|min:0',
            'skor_pkg' => 'nullable|numeric|min:0|max:100',
            'predikat_pkg' => 'nullable|string',
            'kategori' => 'required|string',
            'catatan' => 'nullable|string',
        ]);

        $kinerjaGuru->update($validated);

        return redirect()->route('admin.kepegawaian')->with('success', 'Data kinerja berhasil diperbarui.');
    }

    public function destroyKinerja(KinerjaGuru $kinerjaGuru)
    {
        $kinerjaGuru->delete();

        return redirect()->route('admin.kepegawaian')->with('success', 'Data kinerja berhasil dihapus.');
    }

    // === SERTIFIKASI GURU ===
    public function storeSertifikasi(Request $request)
    {
        $validated = $request->validate([
            'guru_id' => 'required|exists:guru,id',
            'jenis_sertifikasi' => 'required|string',
            'nomor_sertifikat' => 'required|string|max:100',
            'tahun_sertifikasi' => 'required|integer|min:1990|max:2099',
            'bidang_studi' => 'nullable|string|max:255',
            'penerbit' => 'required|string|max:255',
            'file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('file')) {
            $validated['file'] = Storage::url($request->file('file')->store('sertifikasi-guru', 'public'));
        }

        SertifikasiGuru::create($validated);

        return redirect()->route('admin.kepegawaian')->with('success', 'Data sertifikasi berhasil ditambahkan.');
    }

    public function updateSertifikasi(Request $request, SertifikasiGuru $sertifikasiGuru)
    {
        $validated = $request->validate([
            'jenis_sertifikasi' => 'required|string',
            'nomor_sertifikat' => 'required|string|max:100',
            'tahun_sertifikasi' => 'required|integer|min:1990|max:2099',
            'bidang_studi' => 'nullable|string|max:255',
            'penerbit' => 'required|string|max:255',
            'file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('file')) {
            if ($sertifikasiGuru->file) {
                $oldPath = str_replace('/storage/', '', $sertifikasiGuru->file);
                Storage::disk('public')->delete($oldPath);
            }
            $validated['file'] = Storage::url($request->file('file')->store('sertifikasi-guru', 'public'));
        }

        $sertifikasiGuru->update($validated);

        return redirect()->route('admin.kepegawaian')->with('success', 'Data sertifikasi berhasil diperbarui.');
    }

    public function destroySertifikasi(SertifikasiGuru $sertifikasiGuru)
    {
        if ($sertifikasiGuru->file) {
            $oldPath = str_replace('/storage/', '', $sertifikasiGuru->file);
            Storage::disk('public')->delete($oldPath);
        }
        $sertifikasiGuru->delete();

        return redirect()->route('admin.kepegawaian')->with('success', 'Data sertifikasi berhasil dihapus.');
    }

    // === TUNJANGAN GURU ===
    public function storeTunjangan(Request $request)
    {
        $validated = $request->validate([
            'guru_id' => 'required|exists:guru,id',
            'jenis_tunjangan' => 'required|string',
            'besaran' => 'required|numeric|min:0',
            'periode_bulan' => 'required|string',
            'periode_tahun' => 'required|integer|min:2000|max:2099',
            'status' => 'required|in:dibayarkan,menunggu,ditangguhkan',
            'tanggal_bayar' => 'nullable|date',
            'keterangan' => 'nullable|string',
        ]);

        TunjanganGuru::create($validated);

        return redirect()->route('admin.kepegawaian')->with('success', 'Data tunjangan berhasil ditambahkan.');
    }

    public function updateTunjangan(Request $request, TunjanganGuru $tunjanganGuru)
    {
        $validated = $request->validate([
            'jenis_tunjangan' => 'required|string',
            'besaran' => 'required|numeric|min:0',
            'periode_bulan' => 'required|string',
            'periode_tahun' => 'required|integer|min:2000|max:2099',
            'status' => 'required|in:dibayarkan,menunggu,ditangguhkan',
            'tanggal_bayar' => 'nullable|date',
            'keterangan' => 'nullable|string',
        ]);

        $tunjanganGuru->update($validated);

        return redirect()->route('admin.kepegawaian')->with('success', 'Data tunjangan berhasil diperbarui.');
    }

    public function destroyTunjangan(TunjanganGuru $tunjanganGuru)
    {
        $tunjanganGuru->delete();

        return redirect()->route('admin.kepegawaian')->with('success', 'Data tunjangan berhasil dihapus.');
    }
}
