<?php

use App\Enums\UserRole;
use App\Http\Controllers\Admin\AbsensiController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\AkademikController;
use App\Http\Controllers\Admin\BackupController;
use App\Http\Controllers\Admin\BarangController;
use App\Http\Controllers\Admin\FileManagerController;
use App\Http\Controllers\Admin\BeasiswaController;
use App\Http\Controllers\Admin\BeritaController as AdminBeritaController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EkskulController;
use App\Http\Controllers\Admin\ExportController;
use App\Http\Controllers\Admin\GuruController;
use App\Http\Controllers\Admin\ImportController;
use App\Http\Controllers\Admin\IzinSiswaController;
use App\Http\Controllers\Admin\JadwalPiketController;
use App\Http\Controllers\Admin\JurusanController;
use App\Http\Controllers\Admin\KelasController;
use App\Http\Controllers\Admin\KepegawaianController;
use App\Http\Controllers\Admin\KeuanganController;
use App\Http\Controllers\Admin\KonselingController;
use App\Http\Controllers\Admin\KontakController;
use App\Http\Controllers\Admin\KurikulumController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\LeggerController;
use App\Http\Controllers\Admin\MaintenanceAsetController;
use App\Http\Controllers\Admin\MapelController;
use App\Http\Controllers\Admin\MutasiSiswaController;
use App\Http\Controllers\Admin\NilaiController;
use App\Http\Controllers\Admin\NotificationController as AdminNotificationController;
use App\Http\Controllers\Admin\PelanggaranController;
use App\Http\Controllers\Admin\PeminjamanAsetController;
use App\Http\Controllers\Admin\PengaturanController;
use App\Http\Controllers\Admin\PengumumanController;
use App\Http\Controllers\Admin\PesanController as AdminPesanController;
use App\Http\Controllers\Admin\PpdbController;
use App\Http\Controllers\Admin\PresensiGuruController as AdminPresensiGuruController;
use App\Http\Controllers\Admin\PrestasiController;
use App\Http\Controllers\Admin\RaporController as AdminRaporController;
use App\Http\Controllers\Admin\RemedialController;
use App\Http\Controllers\Admin\RuangController;
use App\Http\Controllers\Admin\SemesterController;
use App\Http\Controllers\Admin\SiswaController;
use App\Http\Controllers\Admin\SoalController;
use App\Http\Controllers\Admin\TracerStudyController;
use App\Http\Controllers\Admin\UjianController;
use App\Http\Controllers\AlumniController;
use App\Http\Controllers\Api\MidtransWebhookController;
use App\Http\Controllers\Api\WebPushController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\Portal\Guru\AbsensiController as GuruAbsensiController;
use App\Http\Controllers\Portal\Guru\BankSoalController as GuruBankSoalController;
use App\Http\Controllers\Portal\Guru\JadwalController as GuruJadwalController;
use App\Http\Controllers\Portal\Guru\ModulController as GuruModulController;
use App\Http\Controllers\Portal\Guru\NilaiController as GuruNilaiController;
use App\Http\Controllers\Portal\Guru\PerpustakaanController as GuruPerpustakaanController;
use App\Http\Controllers\Portal\Guru\PesanController as GuruPesanController;
use App\Http\Controllers\Portal\Guru\PresensiGuruController as GuruPresensiGuruController;
use App\Http\Controllers\Portal\Guru\ProfilController as GuruProfilController;
use App\Http\Controllers\Portal\Guru\RaporController as GuruRaporController;
use App\Http\Controllers\Portal\Guru\TugasController as GuruTugasController;
use App\Http\Controllers\Portal\Guru\UjianOnlineController as GuruUjianOnlineController;
use App\Http\Controllers\Portal\Guru\WaliKelasController;
use App\Http\Controllers\Portal\GuruDashboardController;
use App\Http\Controllers\Portal\Siswa\AbsensiController as SiswaAbsensiController;
use App\Http\Controllers\Portal\Siswa\EkskulController as SiswaEkskulController;
use App\Http\Controllers\Portal\Siswa\JadwalController as SiswaJadwalController;
use App\Http\Controllers\Portal\Siswa\ModulController as SiswaModulController;
use App\Http\Controllers\Portal\Siswa\NilaiController as SiswaNilaiController;
use App\Http\Controllers\Portal\Siswa\PerpustakaanController as SiswaPerpustakaanController;
use App\Http\Controllers\Portal\Siswa\PesanController as SiswaPesanController;
use App\Http\Controllers\Portal\Siswa\ProfilController as SiswaProfilController;
use App\Http\Controllers\Portal\Siswa\SppController;
use App\Http\Controllers\Portal\Siswa\SppInvoiceController;
use App\Http\Controllers\Portal\Siswa\SppPaymentController;
use App\Http\Controllers\Portal\Siswa\TugasController as SiswaTugasController;
use App\Http\Controllers\Portal\Siswa\UjianOnlineController as SiswaUjianOnlineController;
use App\Http\Controllers\Portal\SiswaDashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Ujian\BerlangsungController;
use App\Http\Controllers\Ujian\HasilController;
use App\Models\Berita;
use App\Models\Guru;
use App\Models\IzinSiswa;
use App\Models\KontakMessage;
use App\Models\Pengumuman;
use App\Models\Prestasi;
use App\Models\Siswa;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::get('/', function () {
    $totalSiswa = Siswa::count();
    $totalGuru = Guru::count();
    $totalPrestasi = Prestasi::count();
    $beritaUtama = Berita::where('is_utama', true)->latest()->first();
    $featuredId = $beritaUtama?->id;
    $beritaList = Berita::when($featuredId, fn ($q) => $q->where('id', '!=', $featuredId))
        ->latest()->take(2)->get();
    $pengumuman = Pengumuman::latest()->take(6)->get();

    return view('home', compact(
        'totalSiswa', 'totalGuru', 'totalPrestasi',
        'beritaUtama', 'beritaList', 'pengumuman'
    ));
})->name('home');

Route::get('/profil-sekolah', function () {
    return view('profil-sekolah');
})->name('profil-sekolah');

Route::get('/akademik', function () {
    return view('akademik');
})->name('akademik');

Route::get('/berita', [BeritaController::class, 'index'])->name('berita');
Route::get('/berita/{slug}', [BeritaController::class, 'show'])->name('berita.show');

Route::get('/alumni', [AlumniController::class, 'index'])->name('alumni');
Route::post('/alumni/tracer', [AlumniController::class, 'tracerStore'])->name('alumni.tracer')->middleware('throttle:5,1');

Route::get('/izin-siswa', function () {
    return view('izin-siswa');
})->name('izin-siswa.form');

Route::post('/izin-siswa', function (Request $request) {
    $validated = $request->validate([
        'nisn' => 'required|string|exists:siswa,nisn',
        'tanggal_mulai' => 'required|date',
        'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        'alasan' => 'required|string|max:255',
        'keterangan' => 'nullable|string',
        'file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
    ]);

    $siswa = Siswa::where('nisn', $validated['nisn'])->first();

    if (! $siswa) {
        return back()->withErrors(['nisn' => 'NISN tidak ditemukan.'])->withInput();
    }

    $data = [
        'siswa_id' => $siswa->id,
        'tanggal_mulai' => $validated['tanggal_mulai'],
        'tanggal_selesai' => $validated['tanggal_selesai'],
        'alasan' => $validated['alasan'],
        'keterangan' => $validated['keterangan'] ?? null,
    ];

    if ($request->hasFile('file')) {
        $data['file'] = Storage::url($request->file('file')->store('izin-siswa', 'public'));
    }

    IzinSiswa::create($data);

    return redirect()->route('izin-siswa.form')->with('status', 'Pengajuan izin berhasil dikirim! Menunggu persetujuan.');
})->name('izin-siswa.submit')->middleware('throttle:5,1');

// PPDB
Route::get('/ppdb', [PendaftaranController::class, 'index'])->name('ppdb.form');
Route::post('/ppdb', [PendaftaranController::class, 'store'])->name('ppdb.store')->middleware('throttle:5,1');
Route::get('/ppdb/success/{noPendaftaran}', [PendaftaranController::class, 'success'])->name('ppdb.success');
Route::get('/ppdb/cek-status', [PendaftaranController::class, 'cekStatus'])->name('ppdb.cek-status');
Route::get('/ppdb/berkas/{berkas}', [PendaftaranController::class, 'downloadBerkas'])->name('ppdb.download-berkas');

Route::get('/kontak', function () {
    return view('kontak');
})->name('kontak');

Route::post('/kontak', function (Request $request) {
    $validated = $request->validate([
        'nama' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'subjek' => 'required|string|max:255',
        'pesan' => 'required|string',
    ]);

    KontakMessage::create($validated);

    return redirect()->route('kontak')->with('status', 'Pesan berhasil dikirim! Kami akan menghubungi Anda segera.');
})->name('kontak.kirim')->middleware('throttle:5,1');

Route::get('/dashboard', function () {
    $role = auth()->user()->role ?? null;
    if ($role === UserRole::Admin) {
        return redirect()->route('admin.dashboard');
    }
    if ($role === UserRole::Guru) {
        return redirect()->route('portal-guru.dashboard');
    }
    if ($role === UserRole::Siswa) {
        return redirect()->route('portal-siswa.dashboard');
    }

    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/data-siswa', [SiswaController::class, 'index'])->name('data-siswa');
    Route::get('/data-siswa/{siswa}', [SiswaController::class, 'show'])->name('siswa.show');
    Route::post('/data-siswa', [SiswaController::class, 'store'])->name('siswa.store');
    Route::post('/import-siswa/preview', [ImportController::class, 'previewSiswa'])->name('import.siswa.preview');
    Route::post('/import-siswa', [ImportController::class, 'importSiswa'])->name('import.siswa');
    Route::put('/data-siswa/{siswa}', [SiswaController::class, 'update'])->name('siswa.update');
    Route::delete('/data-siswa/{siswa}', [SiswaController::class, 'destroy'])->name('siswa.destroy');
    Route::post('/data-siswa/bulk-delete', [SiswaController::class, 'bulkDestroy'])->name('siswa.bulk-destroy');
    Route::post('/data-siswa/bulk-restore', [SiswaController::class, 'bulkRestore'])->name('siswa.bulk-restore');
    Route::get('/data-guru', [GuruController::class, 'index'])->name('guru');
    Route::get('/data-guru/{guru}', [GuruController::class, 'show'])->name('guru.show');
    Route::post('/data-guru', [GuruController::class, 'store'])->name('guru.store');
    Route::put('/data-guru/{guru}', [GuruController::class, 'update'])->name('guru.update');
    Route::delete('/data-guru/{guru}', [GuruController::class, 'destroy'])->name('guru.destroy');
    Route::post('/data-guru/bulk-delete', [GuruController::class, 'bulkDestroy'])->name('guru.bulk-destroy');
    Route::post('/data-guru/bulk-restore', [GuruController::class, 'bulkRestore'])->name('guru.bulk-restore');
    Route::get('/akademik', [AkademikController::class, 'index'])->name('akademik');
    Route::get('/akademik/{jadwal}', [AkademikController::class, 'show'])->name('akademik.show');
    Route::post('/akademik', [AkademikController::class, 'store'])->name('akademik.store');
    Route::put('/akademik/{jadwal}', [AkademikController::class, 'update'])->name('akademik.update');
    Route::delete('/akademik/{jadwal}', [AkademikController::class, 'destroy'])->name('akademik.destroy');
    Route::get('/nilai', [NilaiController::class, 'index'])->name('nilai');
    Route::get('/nilai/{nilai}', [NilaiController::class, 'show'])->name('nilai.show');
    Route::post('/nilai', [NilaiController::class, 'store'])->name('nilai.store');
    Route::put('/nilai/{nilai}', [NilaiController::class, 'update'])->name('nilai.update');
    Route::delete('/nilai/{nilai}', [NilaiController::class, 'destroy'])->name('nilai.destroy');
    Route::get('/mapel', [MapelController::class, 'index'])->name('mapel');
    Route::get('/mapel/{mapel}', [MapelController::class, 'show'])->name('mapel.show');
    Route::post('/mapel', [MapelController::class, 'store'])->name('mapel.store');
    Route::put('/mapel/{mapel}', [MapelController::class, 'update'])->name('mapel.update');
    Route::delete('/mapel/{mapel}', [MapelController::class, 'destroy'])->name('mapel.destroy');
    Route::get('/kelas', [KelasController::class, 'index'])->name('kelas');
    Route::get('/kelas/{kelas}', [KelasController::class, 'show'])->name('kelas.show');
    Route::post('/kelas', [KelasController::class, 'store'])->name('kelas.store');
    Route::put('/kelas/{kelas}', [KelasController::class, 'update'])->name('kelas.update');
    Route::delete('/kelas/{kelas}', [KelasController::class, 'destroy'])->name('kelas.destroy');
    Route::get('/keuangan', [KeuanganController::class, 'index'])->name('keuangan');
    Route::get('/keuangan/{keuangan}', [KeuanganController::class, 'show'])->name('keuangan.show');
    Route::post('/keuangan', [KeuanganController::class, 'store'])->name('keuangan.store');
    Route::put('/keuangan/{keuangan}', [KeuanganController::class, 'update'])->name('keuangan.update');
    Route::delete('/keuangan/{keuangan}', [KeuanganController::class, 'destroy'])->name('keuangan.destroy');
    Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensi');
    Route::get('/absensi/{absensi}', [AbsensiController::class, 'show'])->name('absensi.show');
    Route::post('/absensi', [AbsensiController::class, 'store'])->name('absensi.store');
    Route::put('/absensi/{absensi}', [AbsensiController::class, 'update'])->name('absensi.update');
    Route::delete('/absensi/{absensi}', [AbsensiController::class, 'destroy'])->name('absensi.destroy');
    Route::get('/pengumuman', [PengumumanController::class, 'index'])->name('pengumuman');
    Route::get('/pengumuman/{pengumuman}', [PengumumanController::class, 'show'])->name('pengumuman.show');
    Route::post('/pengumuman', [PengumumanController::class, 'store'])->name('pengumuman.store');
    Route::put('/pengumuman/{pengumuman}', [PengumumanController::class, 'update'])->name('pengumuman.update');
    Route::delete('/pengumuman/{pengumuman}', [PengumumanController::class, 'destroy'])->name('pengumuman.destroy');
    Route::get('/prestasi', [PrestasiController::class, 'index'])->name('prestasi');
    Route::get('/prestasi/{prestasi}', [PrestasiController::class, 'show'])->name('prestasi.show');
    Route::post('/prestasi', [PrestasiController::class, 'store'])->name('prestasi.store');
    Route::put('/prestasi/{prestasi}', [PrestasiController::class, 'update'])->name('prestasi.update');
    Route::delete('/prestasi/{prestasi}', [PrestasiController::class, 'destroy'])->name('prestasi.destroy');
    Route::get('/jurusan', [JurusanController::class, 'index'])->name('jurusan');
    Route::get('/jurusan/{jurusan}', [JurusanController::class, 'show'])->name('jurusan.show');
    Route::post('/jurusan', [JurusanController::class, 'store'])->name('jurusan.store');
    Route::put('/jurusan/{jurusan}', [JurusanController::class, 'update'])->name('jurusan.update');
    Route::delete('/jurusan/{jurusan}', [JurusanController::class, 'destroy'])->name('jurusan.destroy');
    Route::get('/pengaturan', [PengaturanController::class, 'index'])->name('pengaturan');
    Route::get('/pengaturan-website', [PengaturanController::class, 'website'])->name('pengaturan-website');
    Route::post('/pengaturan-website', [PengaturanController::class, 'websiteStore'])->name('pengaturan-website.store');
    Route::get('/ujian-online', [UjianController::class, 'index'])->name('ujian-online');
    Route::get('/ujian-online/{ujian}', [UjianController::class, 'show'])->name('ujian-online.show');
    Route::post('/ujian-online', [UjianController::class, 'store'])->name('ujian-online.store');
    Route::put('/ujian-online/{ujian}', [UjianController::class, 'update'])->name('ujian-online.update');
    Route::delete('/ujian-online/{ujian}', [UjianController::class, 'destroy'])->name('ujian-online.destroy');
    Route::get('/berita', [AdminBeritaController::class, 'index'])->name('berita');
    Route::post('/berita', [AdminBeritaController::class, 'store'])->name('berita.store');
    Route::put('/berita/{berita}', [AdminBeritaController::class, 'update'])->name('berita.update');
    Route::delete('/berita/{berita}', [AdminBeritaController::class, 'destroy'])->name('berita.destroy');
    Route::post('/berita/bulk-delete', [AdminBeritaController::class, 'bulkDestroy'])->name('berita.bulk-destroy');
    Route::post('/berita/bulk-restore', [AdminBeritaController::class, 'bulkRestore'])->name('berita.bulk-restore');
    Route::post('/berita/upload-image', [AdminBeritaController::class, 'uploadImage'])->name('berita.upload-image');
    Route::get('/bank-soal', [SoalController::class, 'index'])->name('bank-soal');
    Route::get('/bank-soal/{soal}', [SoalController::class, 'show'])->name('bank-soal.show');
    Route::post('/bank-soal', [SoalController::class, 'store'])->name('bank-soal.store');
    Route::put('/bank-soal/{soal}', [SoalController::class, 'update'])->name('bank-soal.update');
    Route::delete('/bank-soal/{soal}', [SoalController::class, 'destroy'])->name('bank-soal.destroy');
    Route::post('/bank-soal/bulk-delete', [SoalController::class, 'bulkDestroy'])->name('bank-soal.bulk-destroy');
    Route::post('/bank-soal/bulk-restore', [SoalController::class, 'bulkRestore'])->name('bank-soal.bulk-restore');
    Route::post('/bank-soal/import', [SoalController::class, 'import'])->name('bank-soal.import');
    Route::get('/notifications', [AdminNotificationController::class, 'index'])->name('notifications');
    Route::match(['GET', 'POST'], '/notifications/read/{id}', [AdminNotificationController::class, 'read'])->name('notifications.read');
    Route::post('/notifications/read-all', [AdminNotificationController::class, 'readAll'])->name('notifications.read-all');
    Route::get('/export/siswa', [ExportController::class, 'siswa'])->name('export.siswa');
    Route::post('/export/siswa/async', [ExportController::class, 'siswaAsync'])->name('export.siswa.async');
    Route::get('/export/nilai', [ExportController::class, 'nilai'])->name('export.nilai');
    Route::post('/export/nilai/async', [ExportController::class, 'nilaiAsync'])->name('export.nilai.async');
    Route::get('/export/absensi', [ExportController::class, 'absensi'])->name('export.absensi');
    Route::post('/export/absensi/async', [ExportController::class, 'absensiAsync'])->name('export.absensi.async');
    Route::get('/export/download/{path}', [ExportController::class, 'download'])->name('export.download')->where('path', '.*');
    Route::get('/kontak', [KontakController::class, 'index'])->name('kontak');
    Route::get('/kontak/{kontakMessage}', [KontakController::class, 'show'])->name('kontak.show');
    Route::post('/kontak/{kontakMessage}/read', [KontakController::class, 'markRead'])->name('kontak.mark-read');
    Route::delete('/kontak/{kontakMessage}', [KontakController::class, 'destroy'])->name('kontak.destroy');
    Route::get('/tracer-study', [TracerStudyController::class, 'index'])->name('tracer-study');
    Route::delete('/tracer-study/{tracerStudy}', [TracerStudyController::class, 'destroy'])->name('tracer-study.destroy');
    Route::get('/activity-log', [ActivityLogController::class, 'index'])->name('activity-log');
    Route::get('/ekskul', [EkskulController::class, 'index'])->name('ekskul');
    Route::post('/ekskul', [EkskulController::class, 'store'])->name('ekskul.store');
    Route::put('/ekskul/{ekskul}', [EkskulController::class, 'update'])->name('ekskul.update');
    Route::delete('/ekskul/{ekskul}', [EkskulController::class, 'destroy'])->name('ekskul.destroy');
    Route::get('/ekskul/{ekskul}/anggota', [EkskulController::class, 'anggota'])->name('ekskul.anggota');
    Route::post('/ekskul/{ekskul}/anggota', [EkskulController::class, 'addAnggota'])->name('ekskul.anggota.add');
    Route::delete('/ekskul/{ekskul}/anggota/{siswa}', [EkskulController::class, 'removeAnggota'])->name('ekskul.anggota.remove');
    Route::get('/semester', [SemesterController::class, 'index'])->name('semester');
    Route::post('/semester', [SemesterController::class, 'store'])->name('semester.store');
    Route::put('/semester/{semester}', [SemesterController::class, 'update'])->name('semester.update');
    Route::post('/semester/{semester}/set-active', [SemesterController::class, 'setActive'])->name('semester.set-active');
    Route::delete('/semester/{semester}', [SemesterController::class, 'destroy'])->name('semester.destroy');
    Route::post('/semester/event', [SemesterController::class, 'storeEvent'])->name('semester.event.store');
    Route::delete('/semester/event/{kalender}', [SemesterController::class, 'destroyEvent'])->name('semester.event.destroy');

    // Kurikulum
    Route::get('/kurikulum', [KurikulumController::class, 'index'])->name('kurikulum');
    Route::post('/kurikulum/ki-kd', [KurikulumController::class, 'storeKiKd'])->name('kurikulum.store-ki-kd');
    Route::post('/kurikulum/ki-kd/{kiKd}', [KurikulumController::class, 'updateKiKd'])->name('kurikulum.update-ki-kd');
    Route::delete('/kurikulum/ki-kd/{kiKd}', [KurikulumController::class, 'destroyKiKd'])->name('kurikulum.destroy-ki-kd');
    Route::post('/kurikulum/silabus', [KurikulumController::class, 'storeSilabus'])->name('kurikulum.store-silabus');
    Route::post('/kurikulum/silabus/{silabus}', [KurikulumController::class, 'updateSilabus'])->name('kurikulum.update-silabus');
    Route::delete('/kurikulum/silabus/{silabus}', [KurikulumController::class, 'destroySilabus'])->name('kurikulum.destroy-silabus');
    Route::post('/kurikulum/prota', [KurikulumController::class, 'storeProta'])->name('kurikulum.store-prota');
    Route::get('/kurikulum/prota/{prota}', [KurikulumController::class, 'showProta'])->name('kurikulum.show-prota');
    Route::delete('/kurikulum/prota/{prota}', [KurikulumController::class, 'destroyProta'])->name('kurikulum.destroy-prota');

    // Jadwal Piket
    Route::get('/jadwal-piket', [JadwalPiketController::class, 'index'])->name('jadwal-piket');
    Route::post('/jadwal-piket', [JadwalPiketController::class, 'store'])->name('jadwal-piket.store');
    Route::delete('/jadwal-piket/{jadwalPiket}', [JadwalPiketController::class, 'destroy'])->name('jadwal-piket.destroy');

    // Presensi Guru
    Route::get('/presensi-guru', [AdminPresensiGuruController::class, 'index'])->name('presensi-guru');
    Route::put('/presensi-guru/{presensiGuru}', [AdminPresensiGuruController::class, 'update'])->name('presensi-guru.update');
    Route::post('/presensi-guru/generate-qr', [AdminPresensiGuruController::class, 'generateQr'])->name('presensi-guru.generate-qr');
    Route::delete('/presensi-guru/{presensiGuru}', [AdminPresensiGuruController::class, 'destroy'])->name('presensi-guru.destroy');

    // Kepegawaian
    Route::get('/kepegawaian', [KepegawaianController::class, 'index'])->name('kepegawaian');
    Route::post('/kepegawaian/kepegawaian', [KepegawaianController::class, 'storeKepegawaian'])->name('kepegawaian.store-kepegawaian');
    Route::put('/kepegawaian/kepegawaian/{kepegawaian}', [KepegawaianController::class, 'updateKepegawaian'])->name('kepegawaian.update-kepegawaian');
    Route::delete('/kepegawaian/kepegawaian/{kepegawaian}', [KepegawaianController::class, 'destroyKepegawaian'])->name('kepegawaian.destroy-kepegawaian');
    Route::post('/kepegawaian/cuti', [KepegawaianController::class, 'storeCuti'])->name('kepegawaian.store-cuti');
    Route::put('/kepegawaian/cuti/{cutiGuru}', [KepegawaianController::class, 'updateCuti'])->name('kepegawaian.update-cuti');
    Route::delete('/kepegawaian/cuti/{cutiGuru}', [KepegawaianController::class, 'destroyCuti'])->name('kepegawaian.destroy-cuti');
    Route::post('/kepegawaian/kinerja', [KepegawaianController::class, 'storeKinerja'])->name('kepegawaian.store-kinerja');
    Route::put('/kepegawaian/kinerja/{kinerjaGuru}', [KepegawaianController::class, 'updateKinerja'])->name('kepegawaian.update-kinerja');
    Route::delete('/kepegawaian/kinerja/{kinerjaGuru}', [KepegawaianController::class, 'destroyKinerja'])->name('kepegawaian.destroy-kinerja');
    Route::post('/kepegawaian/sertifikasi', [KepegawaianController::class, 'storeSertifikasi'])->name('kepegawaian.store-sertifikasi');
    Route::put('/kepegawaian/sertifikasi/{sertifikasiGuru}', [KepegawaianController::class, 'updateSertifikasi'])->name('kepegawaian.update-sertifikasi');
    Route::delete('/kepegawaian/sertifikasi/{sertifikasiGuru}', [KepegawaianController::class, 'destroySertifikasi'])->name('kepegawaian.destroy-sertifikasi');
    Route::post('/kepegawaian/tunjangan', [KepegawaianController::class, 'storeTunjangan'])->name('kepegawaian.store-tunjangan');
    Route::put('/kepegawaian/tunjangan/{tunjanganGuru}', [KepegawaianController::class, 'updateTunjangan'])->name('kepegawaian.update-tunjangan');
    Route::delete('/kepegawaian/tunjangan/{tunjanganGuru}', [KepegawaianController::class, 'destroyTunjangan'])->name('kepegawaian.destroy-tunjangan');

    // Legger Nilai
    Route::get('/legger', [LeggerController::class, 'index'])->name('legger');

    // Remedial
    Route::get('/remedial', [RemedialController::class, 'index'])->name('remedial');
    Route::post('/remedial', [RemedialController::class, 'store'])->name('remedial.store');
    Route::put('/remedial/{remedial}', [RemedialController::class, 'update'])->name('remedial.update');
    Route::delete('/remedial/{remedial}', [RemedialController::class, 'destroy'])->name('remedial.destroy');
    Route::get('/remedial/get-siswa', [RemedialController::class, 'getSiswaByKelas'])->name('remedial.get-siswa');

    // Rapor
    Route::get('/rapor', [AdminRaporController::class, 'index'])->name('rapor');
    Route::get('/rapor/{siswa}', [AdminRaporController::class, 'show'])->name('rapor.show');

    // Kesiswaan - Izin Siswa
    Route::get('/izin-siswa', [IzinSiswaController::class, 'index'])->name('izin-siswa');
    Route::post('/izin-siswa/{izinSiswa}/approve', [IzinSiswaController::class, 'approve'])->name('izin-siswa.approve');
    Route::post('/izin-siswa/{izinSiswa}/reject', [IzinSiswaController::class, 'reject'])->name('izin-siswa.reject');
    Route::delete('/izin-siswa/{izinSiswa}', [IzinSiswaController::class, 'destroy'])->name('izin-siswa.destroy');

    // Kesiswaan - Pelanggaran
    Route::get('/pelanggaran-siswa', [PelanggaranController::class, 'index'])->name('pelanggaran-siswa');
    Route::post('/pelanggaran-siswa', [PelanggaranController::class, 'store'])->name('pelanggaran-siswa.store');
    Route::put('/pelanggaran-siswa/{pelanggaranSiswa}', [PelanggaranController::class, 'update'])->name('pelanggaran-siswa.update');
    Route::delete('/pelanggaran-siswa/{pelanggaranSiswa}', [PelanggaranController::class, 'destroy'])->name('pelanggaran-siswa.destroy');
    Route::post('/pelanggaran-siswa/kategori', [PelanggaranController::class, 'storeKategori'])->name('pelanggaran-siswa.store-kategori');
    Route::post('/pelanggaran-siswa/kategori/{kategoriPelanggaran}', [PelanggaranController::class, 'updateKategori'])->name('pelanggaran-siswa.update-kategori');
    Route::delete('/pelanggaran-siswa/kategori/{kategoriPelanggaran}', [PelanggaranController::class, 'destroyKategori'])->name('pelanggaran-siswa.destroy-kategori');
    Route::get('/pelanggaran-siswa/get-siswa', [PelanggaranController::class, 'getSiswaByKelas'])->name('pelanggaran-siswa.get-siswa');

    // Kesiswaan - Mutasi
    Route::get('/mutasi-siswa', [MutasiSiswaController::class, 'index'])->name('mutasi-siswa');
    Route::post('/mutasi-siswa', [MutasiSiswaController::class, 'store'])->name('mutasi-siswa.store');
    Route::delete('/mutasi-siswa/{mutasiSiswa}', [MutasiSiswaController::class, 'destroy'])->name('mutasi-siswa.destroy');
    Route::get('/mutasi-siswa/get-siswa', [MutasiSiswaController::class, 'getSiswaByKelas'])->name('mutasi-siswa.get-siswa');

    // Kesiswaan - Beasiswa
    Route::get('/beasiswa', [BeasiswaController::class, 'index'])->name('beasiswa');
    Route::post('/beasiswa', [BeasiswaController::class, 'store'])->name('beasiswa.store');
    Route::put('/beasiswa/{beasiswa}', [BeasiswaController::class, 'update'])->name('beasiswa.update');
    Route::delete('/beasiswa/{beasiswa}', [BeasiswaController::class, 'destroy'])->name('beasiswa.destroy');

    // Kesiswaan - BK
    Route::get('/konseling', [KonselingController::class, 'index'])->name('konseling');
    Route::post('/konseling', [KonselingController::class, 'store'])->name('konseling.store');
    Route::put('/konseling/{konseling}', [KonselingController::class, 'update'])->name('konseling.update');
    Route::delete('/konseling/{konseling}', [KonselingController::class, 'destroy'])->name('konseling.destroy');
    Route::get('/konseling/get-siswa', [KonselingController::class, 'getSiswaByKelas'])->name('konseling.get-siswa');

    // Pesan Internal
    Route::get('/pesan', [AdminPesanController::class, 'index'])->name('pesan');
    Route::get('/pesan/{conversation}', [AdminPesanController::class, 'show'])->name('pesan.show');
    Route::post('/pesan', [AdminPesanController::class, 'store'])->name('pesan.store');
    Route::post('/pesan/{conversation}/reply', [AdminPesanController::class, 'reply'])->name('pesan.reply');
    Route::put('/pesan/{conversation}/messages/{message}', [AdminPesanController::class, 'update'])->name('pesan.update');
    Route::delete('/pesan/{conversation}/messages', [AdminPesanController::class, 'bulkDestroy'])->name('pesan.bulk-destroy');

    // Aset & Inventaris
    Route::get('/ruang', [RuangController::class, 'index'])->name('ruang');
    Route::get('/ruang/{ruang}', [RuangController::class, 'show'])->name('ruang.show');
    Route::post('/ruang', [RuangController::class, 'store'])->name('ruang.store');
    Route::put('/ruang/{ruang}', [RuangController::class, 'update'])->name('ruang.update');
    Route::delete('/ruang/{ruang}', [RuangController::class, 'destroy'])->name('ruang.destroy');
    Route::get('/barang', [BarangController::class, 'index'])->name('barang');
    Route::get('/barang/{barang}', [BarangController::class, 'show'])->name('barang.show');
    Route::post('/barang', [BarangController::class, 'store'])->name('barang.store');
    Route::put('/barang/{barang}', [BarangController::class, 'update'])->name('barang.update');
    Route::delete('/barang/{barang}', [BarangController::class, 'destroy'])->name('barang.destroy');
    Route::get('/peminjaman-aset', [PeminjamanAsetController::class, 'index'])->name('peminjaman-aset');
    Route::get('/peminjaman-aset/{peminjaman_aset}', [PeminjamanAsetController::class, 'show'])->name('peminjaman-aset.show');
    Route::post('/peminjaman-aset', [PeminjamanAsetController::class, 'store'])->name('peminjaman-aset.store');
    Route::put('/peminjaman-aset/{peminjaman_aset}', [PeminjamanAsetController::class, 'update'])->name('peminjaman-aset.update');
    Route::delete('/peminjaman-aset/{peminjaman_aset}', [PeminjamanAsetController::class, 'destroy'])->name('peminjaman-aset.destroy');
    Route::get('/maintenance-aset', [MaintenanceAsetController::class, 'index'])->name('maintenance-aset');
    Route::get('/maintenance-aset/{maintenance_aset}', [MaintenanceAsetController::class, 'show'])->name('maintenance-aset.show');
    Route::post('/maintenance-aset', [MaintenanceAsetController::class, 'store'])->name('maintenance-aset.store');
    Route::put('/maintenance-aset/{maintenance_aset}', [MaintenanceAsetController::class, 'update'])->name('maintenance-aset.update');
    Route::delete('/maintenance-aset/{maintenance_aset}', [MaintenanceAsetController::class, 'destroy'])->name('maintenance-aset.destroy');

    // PPDB
    Route::get('/ppdb', [PpdbController::class, 'index'])->name('ppdb');
    Route::get('/ppdb/{pendaftaran}/verifikasi', [PpdbController::class, 'verifikasi'])->name('ppdb.verifikasi');
    Route::put('/ppdb/{pendaftaran}/status', [PpdbController::class, 'updateStatus'])->name('ppdb.update-status');
    Route::get('/ppdb/berkas/{berkas}', [PpdbController::class, 'downloadBerkas'])->name('ppdb.download-berkas');
    Route::delete('/ppdb/{pendaftaran}', [PpdbController::class, 'destroy'])->name('ppdb.destroy');

    // Backup Database
    Route::get('/backup', [BackupController::class, 'index'])->name('backup');
    Route::post('/backup/create', [BackupController::class, 'create'])->name('backup.create');
    Route::post('/backup/{filename}/restore', [BackupController::class, 'restore'])->name('backup.restore');
    Route::get('/backup/{filename}/download', [BackupController::class, 'download'])->name('backup.download');
    Route::delete('/backup/{filename}', [BackupController::class, 'destroy'])->name('backup.destroy');

    // File Manager
    Route::get('/file-manager', [FileManagerController::class, 'index'])->name('file-manager');
    Route::post('/file-manager/upload', [FileManagerController::class, 'upload'])->name('file-manager.upload');
    Route::post('/file-manager/create-folder', [FileManagerController::class, 'createFolder'])->name('file-manager.create-folder');
    Route::delete('/file-manager/delete', [FileManagerController::class, 'destroy'])->name('file-manager.destroy');
    Route::delete('/file-manager/bulk-delete', [FileManagerController::class, 'bulkDestroy'])->name('file-manager.bulk-destroy');

    // Laporan & Analitik
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan');
    Route::get('/laporan/siswa/pdf', [LaporanController::class, 'exportSiswaPdf'])->name('laporan.siswa-pdf');
    Route::get('/laporan/guru/pdf', [LaporanController::class, 'exportGuruPdf'])->name('laporan.guru-pdf');
    Route::get('/laporan/jadwal/pdf', [LaporanController::class, 'exportJadwalPdf'])->name('laporan.jadwal-pdf');
    Route::get('/laporan/nilai/pdf', [LaporanController::class, 'exportNilaiPdf'])->name('laporan.nilai-pdf');
    Route::get('/laporan/absensi/pdf', [LaporanController::class, 'exportAbsensiPdf'])->name('laporan.absensi-pdf');
    Route::get('/laporan/rapor/{siswa}/pdf', [LaporanController::class, 'exportRaporPdf'])->name('laporan.rapor-pdf');
});

Route::prefix('portal-siswa')->name('portal-siswa.')->middleware(['auth', 'role:siswa'])->group(function () {
    Route::get('/dashboard', [SiswaDashboardController::class, 'index'])->name('dashboard');
    Route::get('/jadwal', [SiswaJadwalController::class, 'index'])->name('jadwal');
    Route::get('/jadwal/print', [SiswaJadwalController::class, 'print'])->name('jadwal.print');
    Route::get('/nilai', [SiswaNilaiController::class, 'index'])->name('nilai');
    Route::get('/absensi', [SiswaAbsensiController::class, 'index'])->name('absensi');
    Route::get('/tugas', [SiswaTugasController::class, 'index'])->name('tugas');
    Route::post('/tugas', [SiswaTugasController::class, 'store'])->name('tugas.store');
    Route::get('/ujian-online', [SiswaUjianOnlineController::class, 'index'])->name('ujian-online');
    Route::get('/perpustakaan', [SiswaPerpustakaanController::class, 'index'])->name('perpustakaan');
    Route::get('/modul', [SiswaModulController::class, 'index'])->name('modul');
    Route::get('/modul/{modul}/download', [SiswaModulController::class, 'download'])->name('modul.download');
    Route::get('/pesan', [SiswaPesanController::class, 'index'])->name('pesan');
    Route::get('/pesan/{conversation}', [SiswaPesanController::class, 'show'])->name('pesan.show');
    Route::post('/pesan', [SiswaPesanController::class, 'store'])->name('pesan.store');
    Route::post('/pesan/{conversation}/reply', [SiswaPesanController::class, 'reply'])->name('pesan.reply');
    Route::put('/pesan/{conversation}/messages/{message}', [SiswaPesanController::class, 'update'])->name('pesan.update');
    Route::delete('/pesan/{conversation}/messages', [SiswaPesanController::class, 'bulkDestroy'])->name('pesan.bulk-destroy');
    Route::get('/ekskul', [SiswaEkskulController::class, 'index'])->name('ekskul');
    Route::post('/ekskul/{ekskul}/join', [SiswaEkskulController::class, 'join'])->name('ekskul.join');
    Route::delete('/ekskul/{ekskul}/leave', [SiswaEkskulController::class, 'leave'])->name('ekskul.leave');
    Route::get('/profil', [SiswaProfilController::class, 'index'])->name('profil');
    Route::get('/spp', [SppController::class, 'index'])->name('spp');
    Route::post('/spp/{pembayaran}/pay', [SppPaymentController::class, 'pay'])->name('spp.pay');
    Route::get('/spp/{pembayaran}/invoice', [SppInvoiceController::class, 'download'])->name('spp.invoice');
    Route::get('/kartu', function () {
        $siswa = auth()->user()->siswa;
        abort_unless($siswa, 404);
        return view('portal-siswa.kartu', ['siswa' => $siswa, 'user' => auth()->user(), 'sekolah' => 'SMA NUSANTARA']);
    })->name('kartu');
});

Route::prefix('portal-guru')->name('portal-guru.')->middleware(['auth', 'role:guru'])->group(function () {
    Route::get('/dashboard', [GuruDashboardController::class, 'index'])->name('dashboard');
    Route::get('/jadwal', [GuruJadwalController::class, 'index'])->name('jadwal');
    Route::get('/jadwal/print', [GuruJadwalController::class, 'print'])->name('jadwal.print');
    Route::get('/nilai', [GuruNilaiController::class, 'index'])->name('nilai');
    Route::post('/nilai', [GuruNilaiController::class, 'store'])->name('nilai.store');
    Route::get('/absensi', [GuruAbsensiController::class, 'index'])->name('absensi');
    Route::post('/absensi', [GuruAbsensiController::class, 'store'])->name('absensi.store');
    Route::get('/tugas', [GuruTugasController::class, 'index'])->name('tugas');
    Route::post('/tugas', [GuruTugasController::class, 'store'])->name('tugas.store');
    Route::delete('/tugas/{tugas}', [GuruTugasController::class, 'destroy'])->name('tugas.destroy');
    Route::get('/tugas/{tugas}/submissions', [GuruTugasController::class, 'submissions'])->name('tugas.submissions');
    Route::post('/tugas/{tugas}/nilai/{submission}', [GuruTugasController::class, 'nilai'])->name('tugas.nilai');
    Route::get('/bank-soal', [GuruBankSoalController::class, 'index'])->name('bank-soal');
    Route::get('/bank-soal/template', [GuruBankSoalController::class, 'downloadTemplate'])->name('bank-soal.download-template');
    Route::post('/bank-soal/import', [GuruBankSoalController::class, 'import'])->name('bank-soal.import');
    Route::get('/bank-soal/{soal}', [GuruBankSoalController::class, 'show'])->name('bank-soal.show');
    Route::post('/bank-soal', [GuruBankSoalController::class, 'store'])->name('bank-soal.store');
    Route::put('/bank-soal/{soal}', [GuruBankSoalController::class, 'update'])->name('bank-soal.update');
    Route::delete('/bank-soal/{soal}', [GuruBankSoalController::class, 'destroy'])->name('bank-soal.destroy');
    Route::get('/ujian-online', [GuruUjianOnlineController::class, 'index'])->name('ujian-online');
    Route::post('/ujian-online', [GuruUjianOnlineController::class, 'store'])->name('ujian.store');
    Route::put('/ujian-online/{ujian}', [GuruUjianOnlineController::class, 'update'])->name('ujian.update');
    Route::delete('/ujian-online/{ujian}', [GuruUjianOnlineController::class, 'destroy'])->name('ujian.destroy');
    Route::get('/perpustakaan', [GuruPerpustakaanController::class, 'index'])->name('perpustakaan');
    Route::post('/perpustakaan', [GuruPerpustakaanController::class, 'store'])->name('perpustakaan.store');
    Route::put('/perpustakaan/{buku}', [GuruPerpustakaanController::class, 'update'])->name('perpustakaan.update');
    Route::delete('/perpustakaan/{buku}', [GuruPerpustakaanController::class, 'destroy'])->name('perpustakaan.destroy');
    Route::get('/rapor', [GuruRaporController::class, 'index'])->name('rapor');
    Route::get('/rapor/{siswa}', [GuruRaporController::class, 'print'])->name('rapor.print');
    Route::get('/modul', [GuruModulController::class, 'index'])->name('modul');
    Route::post('/modul', [GuruModulController::class, 'store'])->name('modul.store');
    Route::get('/modul/{modul}/download', [GuruModulController::class, 'download'])->name('modul.download');
    Route::delete('/modul/{modul}', [GuruModulController::class, 'destroy'])->name('modul.destroy');
    Route::get('/wali-kelas', [WaliKelasController::class, 'index'])->name('wali-kelas');
    Route::post('/wali-kelas/kirim-pesan', [WaliKelasController::class, 'sendMessage'])->name('wali-kelas.kirim-pesan');
    Route::get('/pesan', [GuruPesanController::class, 'index'])->name('pesan');
    Route::get('/pesan/{conversation}', [GuruPesanController::class, 'show'])->name('pesan.show');
    Route::post('/pesan', [GuruPesanController::class, 'store'])->name('pesan.store');
    Route::post('/pesan/{conversation}/reply', [GuruPesanController::class, 'reply'])->name('pesan.reply');
    Route::put('/pesan/{conversation}/messages/{message}', [GuruPesanController::class, 'update'])->name('pesan.update');
    Route::delete('/pesan/{conversation}/messages', [GuruPesanController::class, 'bulkDestroy'])->name('pesan.bulk-destroy');
    Route::get('/presensi', [GuruPresensiGuruController::class, 'index'])->name('presensi-guru');
    Route::post('/presensi/scan', [GuruPresensiGuruController::class, 'scan'])->name('presensi-guru.scan');
    Route::get('/presensi/scan/{token}', [GuruPresensiGuruController::class, 'scanByToken'])->name('presensi-guru.scan-token');
    Route::get('/profil', [GuruProfilController::class, 'index'])->name('profil');
    Route::get('/kartu', function () {
        $guru = auth()->user()->guru;
        abort_unless($guru, 404);
        return view('portal-guru.kartu', ['guru' => $guru, 'user' => auth()->user(), 'sekolah' => 'SMA NUSANTARA']);
    })->name('kartu');
});

Route::prefix('ujian')->name('ujian.')->middleware(['auth'])->group(function () {
    Route::get('/sedang-berlangsung/{ujian?}', [BerlangsungController::class, 'index'])->name('sedang-berlangsung');
    Route::post('/submit/{ujian}', [BerlangsungController::class, 'submit'])->name('submit');
    Route::get('/hasil/{ujian?}', [HasilController::class, 'index'])->name('hasil');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::post('/api/midtrans/webhook', [MidtransWebhookController::class, 'handle'])
    ->withoutMiddleware(VerifyCsrfToken::class)
    ->name('midtrans.webhook');

Route::post('/api/webpush/subscribe', [WebPushController::class, 'subscribe'])
    ->middleware('auth')->name('webpush.subscribe');
Route::post('/api/webpush/unsubscribe', [WebPushController::class, 'unsubscribe'])
    ->middleware('auth')->name('webpush.unsubscribe');

Route::post('/api/broadcasting/auth', function (Request $request) {
    return Broadcast::auth($request);
})->middleware('auth');

Route::post('/webauthn/challenge', [\App\Http\Controllers\Auth\WebAuthnController::class, 'challenge']);
Route::post('/webauthn/register', [\App\Http\Controllers\Auth\WebAuthnController::class, 'register']);
Route::post('/webauthn/authenticate', [\App\Http\Controllers\Auth\WebAuthnController::class, 'authenticate']);

Route::middleware('auth')->group(function () {
    Route::get('/webauthn/credentials', [\App\Http\Controllers\Auth\WebAuthnController::class, 'credentials']);
    Route::delete('/webauthn/credentials/{id}', [\App\Http\Controllers\Auth\WebAuthnController::class, 'destroy']);
});

require __DIR__.'/auth.php';
