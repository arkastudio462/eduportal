<?php

namespace Database\Seeders;

use App\Models\Absensi;
use App\Models\Barang;
use App\Models\Berita;
use App\Models\Buku;
use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\KontakMessage;
use App\Models\MaintenanceAset;
use App\Models\Mapel;
use App\Models\Nilai;
use App\Models\PembayaranSpp;
use App\Models\PeminjamanAset;
use App\Models\Pengumuman;
use App\Models\Prestasi;
use App\Models\Ruang;
use App\Models\Siswa;
use App\Models\Soal;
use App\Models\Tugas;
use App\Models\TugasSubmission;
use App\Models\Ujian;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(SettingSeeder::class);

        if (User::count() > 0) {
            return;
        }

        // Users
        $admin = User::factory()->create([
            'name' => 'Admin Sekolah',
            'email' => 'admin@eduportal.test',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $guruUsers = [];
        foreach ([
            ['Budi Santoso, S.Pd.', 'guru@eduportal.test'],
            ['Dewi Sartika, S.Pd.', 'dewigs@eduportal.test'],
            ['Ahmad Zainuri, S.Si.', 'ahmadz@eduportal.test'],
        ] as [$name, $email]) {
            $guruUsers[] = User::factory()->create([
                'name' => $name,
                'email' => $email,
                'password' => bcrypt('password'),
                'role' => 'guru',
            ]);
        }

        $siswaUsers = [];
        foreach ([
            ['Ahmad Fauzan', 'siswa@eduportal.test'],
            ['Bunga Citra Lestari', 'bungacl@eduportal.test'],
            ['Cahya Dwi Pratama', 'cahyadp@eduportal.test'],
            ['Dimas Alfian', 'dimasa@eduportal.test'],
            ['Eka Putri Sari', 'ekaps@eduportal.test'],
        ] as [$name, $email]) {
            $siswaUsers[] = User::factory()->create([
                'name' => $name,
                'email' => $email,
                'password' => bcrypt('password'),
                'role' => 'siswa',
            ]);
        }

        // Kelas
        $ipaLabels = ['IPA 1', 'IPA 2', 'IPA 3'];
        $ipsLabels = ['IPS 1', 'IPS 2'];
        $daftarNamaKelas = [];

        $ipaJurusan = Jurusan::create(['nama' => 'IPA', 'kode' => 'IPA']);
        $ipsJurusan = Jurusan::create(['nama' => 'IPS', 'kode' => 'IPS']);

        foreach (['X', 'XI', 'XII'] as $tingkat) {
            foreach ($ipaLabels as $nama) {
                $kelasData[] = Kelas::create([
                    'nama' => "{$tingkat} {$nama}",
                    'tingkat' => $tingkat,
                    'jurusan_id' => $ipaJurusan->id,
                ]);
            }
            foreach ($ipsLabels as $nama) {
                $kelasData[] = Kelas::create([
                    'nama' => "{$tingkat} {$nama}",
                    'tingkat' => $tingkat,
                    'jurusan_id' => $ipsJurusan->id,
                ]);
            }
        }

        // Guru profiles
        $guruModels = [];
        foreach ($guruUsers as $i => $user) {
            $guruModels[] = Guru::create([
                'user_id' => $user->id,
                'nuptk' => '123456789012'.$i,
                'nip' => '198001012024'.str_pad($i, 3, '0', STR_PAD_LEFT),
                'mata_pelajaran' => ['Matematika', 'Bahasa Indonesia', 'Fisika'][$i],
            ]);
        }

        // Assign wali kelas
        foreach ($kelasData as $i => $kelas) {
            $kelas->update(['wali_kelas_id' => $guruModels[$i % count($guruModels)]->id]);
        }

        // Siswa profiles
        foreach ($siswaUsers as $i => $user) {
            Siswa::create([
                'user_id' => $user->id,
                'nis' => '12345'.$i,
                'nisn' => '0023456789'.$i,
                'kelas_id' => $kelasData[$i]->id,
                'status' => 'aktif',
            ]);
        }

        // Mapel
        $mapelNames = [
            'Matematika Wajib', 'Bahasa Indonesia', 'Bahasa Inggris',
            'Fisika', 'Kimia', 'Biologi', 'Sejarah', 'Sosiologi', 'Ekonomi', 'Matematika Minat',
        ];
        $mapels = [];
        foreach ($mapelNames as $name) {
            $mapels[] = Mapel::create([
                'nama' => $name,
                'kode' => strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $name), 0, 3)),
            ]);
        }

        // Jadwal
        $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
        $jamList = [
            ['07:00', '08:30'], ['08:30', '10:00'], ['10:30', '12:00'],
            ['13:00', '14:30'], ['14:30', '16:00'],
        ];
        foreach ($kelasData as $k) {
            foreach (array_slice($hariList, 0, 4) as $h) {
                Jadwal::create([
                    'kelas_id' => $k->id,
                    'mapel_id' => $mapels[array_rand($mapels)]->id,
                    'guru_id' => $guruModels[array_rand($guruModels)]->id,
                    'hari' => $h,
                    'jam_mulai' => $jamList[array_rand($jamList)][0],
                    'jam_selesai' => $jamList[array_rand($jamList)][1],
                    'ruang' => 'Ruang '.fake()->randomElement(['12A', '12B', 'Lab Komp', 'Lab Fisika', 'Aula']),
                ]);
            }
        }

        // Soal
        $tipeSoal = ['PG', 'PG', 'PG', 'Essay', 'Ganda Kompleks'];
        $kesulitan = ['Mudah', 'Sedang', 'Sedang', 'Sulit'];
        foreach ($mapels as $mapel) {
            foreach (range(1, 5) as $j) {
                Soal::create([
                    'mapel_id' => $mapel->id,
                    'tipe' => $tipeSoal[array_rand($tipeSoal)],
                    'kesulitan' => $kesulitan[array_rand($kesulitan)],
                    'konten' => "Soal {$j}: Pertanyaan tentang {$mapel->nama} - ".fake()->sentence(10),
                    'opsi' => json_encode(['A. Pilihan A', 'B. Pilihan B', 'C. Pilihan C', 'D. Pilihan D', 'E. Pilihan E']),
                    'jawaban' => 'A',
                ]);
            }
        }

        // Ujian
        $statuses = ['sedang_berlangsung', 'akan_datang', 'selesai', 'draft'];
        foreach ($mapels as $i => $mapel) {
            Ujian::create([
                'nama' => "UTS {$mapel->nama}",
                'mapel_id' => $mapel->id,
                'tanggal_mulai' => now()->addDays($i - 2),
                'tanggal_selesai' => now()->addDays($i + 1),
                'durasi' => [90, 120, 60][array_rand([90, 120, 60])],
                'status' => $statuses[$i % 4],
            ]);
        }

        // Nilai
        $siswaList = Siswa::all();
        $ujianSelesai = Ujian::where('status', 'selesai')->get();
        foreach ($siswaList as $siswa) {
            foreach ($ujianSelesai as $ujian) {
                $benar = rand(15, 38);
                $salah = rand(2, 15);
                $tidaks = 40 - $benar - $salah;
                Nilai::create([
                    'siswa_id' => $siswa->id,
                    'ujian_id' => $ujian->id,
                    'mapel_id' => $ujian->mapel_id,
                    'jenis' => 'uh',
                    'semester' => now()->year.'/'.(now()->year + 1).' Ganjil',
                    'skor' => round(($benar / 40) * 100, 1),
                    'benar' => $benar,
                    'salah' => $salah,
                    'tidak_dijawab' => $tidaks,
                ]);
            }
        }

        // Pengumuman
        Pengumuman::create(['judul' => 'Jadwal UTS Dirilis', 'konten' => 'Cek jadwal UTS di menu ujian masing-masing kelas.', 'tanggal' => now(), 'tipe' => 'akademik']);
        Pengumuman::create(['judul' => 'Perpustakaan Buka 24 Jam', 'konten' => 'Selama masa UTS, perpustakaan akan buka 24 jam.', 'tanggal' => now()->subDays(2), 'tipe' => 'umum']);
        Pengumuman::create(['judul' => 'Pengumuman Lomba KSN', 'konten' => 'Pendaftaran KSN tingkat kota telah dibuka. Silakan hubungi wali kelas.', 'tanggal' => now()->subDays(5), 'tipe' => 'prestasi']);

        // Pembayaran SPP
        foreach ($siswaList as $siswa) {
            PembayaranSpp::create([
                'siswa_id' => $siswa->id,
                'bulan' => now()->format('m'),
                'tahun' => now()->format('Y'),
                'jumlah' => 750000,
                'status' => 'lunas',
                'tanggal_bayar' => now()->subDays(rand(1, 10)),
            ]);
        }

        // Prestasi
        $prestasiData = [
            ['Juara 1 OSN Tingkat Kota', 'Olimpiade Sains Nasional', 'kota', '1', 'akademik'],
            ['Juara 2 Lomba Debat', 'Lomba Debat Bahasa Inggris', 'provinsi', '2', 'akademik'],
            ['Juara Harapan 1 Porseni', 'Pekan Olahraga dan Seni', 'nasional', 'harapan_1', 'non-akademik'],
        ];
        foreach ($prestasiData as $p) {
            Prestasi::create([
                'judul' => $p[0],
                'deskripsi' => $p[1],
                'tingkat' => $p[2],
                'peringkat' => $p[3],
                'tanggal' => now()->subDays(rand(30, 180)),
                'tipe' => $p[4],
            ]);
        }

        // Absensi
        $statusAbsen = ['hadir', 'hadir', 'hadir', 'hadir', 'sakit', 'izin', 'alpha'];
        foreach ($siswaList as $siswa) {
            foreach (range(1, 20) as $day) {
                Absensi::create([
                    'siswa_id' => $siswa->id,
                    'tanggal' => now()->subDays($day),
                    'status' => $statusAbsen[array_rand($statusAbsen)],
                    'keterangan' => fake()->boolean(20) ? fake()->sentence(3) : null,
                ]);
            }
        }

        // Buku
        $bukuData = [
            ['Matematika Dasar', 'Prof. Dr. Suprapto', 'Erlangga', 'Matematika', 2018, '9786022987654', 10],
            ['Fisika untuk SMA', 'Dr. Bambang Surya', 'Gramedia', 'Fisika', 2019, '9786022998765', 8],
            ['Bahasa Indonesia', 'Tim Kemendikbud', 'Kemendikbud', 'Bahasa', 2020, '9786023009876', 15],
            ['Kimia Modern', 'Dr. Siti Rahayu', 'Yudhistira', 'Kimia', 2019, '9786023010987', 7],
            ['Biologi Sel', 'Prof. Dr. Ir. Budi', 'Penerbit Buku', 'Biologi', 2021, '9786023021098', 5],
            ['Sejarah Indonesia', 'Dr. Ratna Dewi', 'Mediatama', 'Sejarah', 2018, '9786023032109', 12],
            ['English for Daily Use', 'John Smith', 'Oxford Press', 'Bahasa', 2020, '9780194234567', 9],
            ['Sosiologi Dasar', 'Dr. Haryanto', 'Pustaka Ilmu', 'Sosiologi', 2019, '9786023043210', 6],
            ['Ekonomi Makro', 'Prof. Dr. Slamet', 'Gramedia', 'Ekonomi', 2018, '9786023054321', 8],
        ];
        foreach ($bukuData as $b) {
            Buku::create([
                'judul' => $b[0],
                'penulis' => $b[1],
                'penerbit' => $b[2],
                'kategori' => $b[3],
                'tahun_terbit' => $b[4],
                'isbn' => $b[5],
                'stok' => $b[6],
            ]);
        }

        // Tugas
        foreach ($guruModels as $guru) {
            foreach ($kelasData as $kelas) {
                Tugas::create([
                    'guru_id' => $guru->id,
                    'mapel_id' => $mapels[array_rand($mapels)]->id,
                    'kelas_id' => $kelas->id,
                    'judul' => 'Tugas '.fake()->word(),
                    'deskripsi' => fake()->sentence(10),
                    'deadline' => now()->addDays(rand(1, 14)),
                ]);
            }
        }

        // Tugas Submissions
        $tugasSiswa = Tugas::all();
        foreach ($tugasSiswa->take(5) as $tugas) {
            foreach ($siswaList as $siswa) {
                TugasSubmission::create([
                    'tugas_id' => $tugas->id,
                    'siswa_id' => $siswa->id,
                    'catatan' => 'Tugas sudah dikerjakan',
                    'nilai' => rand(60, 100),
                ]);
            }
        }

        // Berita
        $this->call(BeritaSeeder::class);

        // Kontak Messages
        KontakMessage::create([
            'nama' => 'Orang Tua Siswa',
            'email' => 'ortu@example.com',
            'subjek' => 'Informasi Pendaftaran',
            'pesan' => 'Selamat siang, saya ingin menanyakan informasi pendaftaran siswa baru untuk tahun ajaran depan.',
        ]);
        KontakMessage::create([
            'nama' => 'Alumni 2019',
            'email' => 'alumni@example.com',
            'subjek' => 'Tracer Study',
            'pesan' => 'Saya alumni tahun 2019, ingin mengisi tracer study. Mohon dikirimkan formulirnya.',
        ]);

        // Aset & Inventaris - Ruang
        $ruangData = [
            ['R-001', 'Ruang Kelas X IPA 1', '1', 'A', 36, 'kelas', 'tersedia'],
            ['R-002', 'Ruang Kelas X IPA 2', '1', 'A', 36, 'kelas', 'tersedia'],
            ['R-003', 'Ruang Kelas XI IPA 1', '2', 'A', 36, 'kelas', 'tersedia'],
            ['R-004', 'Ruang Kelas XI IPA 2', '2', 'A', 36, 'kelas', 'tersedia'],
            ['R-005', 'Ruang Kelas XII IPA 1', '3', 'A', 36, 'kelas', 'tersedia'],
            ['R-101', 'Laboratorium Fisika', '1', 'B', 30, 'lab', 'tersedia'],
            ['R-102', 'Laboratorium Kimia', '1', 'B', 30, 'lab', 'tersedia'],
            ['R-103', 'Laboratorium Komputer', '2', 'B', 40, 'lab', 'tersedia'],
            ['R-201', 'Perpustakaan', '1', 'A', 50, 'perpustakaan', 'tersedia'],
            ['R-202', 'Aula Serbaguna', '1', 'A', 200, 'aula', 'tersedia'],
            ['R-301', 'Ruang Guru', '3', 'A', 30, 'ruang_guru', 'tersedia'],
            ['R-302', 'Ruang Admin', '1', 'A', 10, 'ruang_admin', 'tersedia'],
        ];
        $ruangModels = [];
        foreach ($ruangData as $r) {
            $ruangModels[] = Ruang::create([
                'kode' => $r[0],
                'nama' => $r[1],
                'lantai' => $r[2],
                'gedung' => $r[3],
                'kapasitas' => $r[4],
                'jenis' => $r[5],
                'status' => $r[6],
            ]);
        }

        // Barang
        $barangData = [
            ['BRG-001', 'Proyektor LCD', 'Elektronik', $ruangModels[0]->id, 1, 'baik', 'Epson EB-2155', 2022, 'BOS'],
            ['BRG-002', 'Laptop ASUS', 'Elektronik', $ruangModels[0]->id, 2, 'baik', 'ASUS VivoBook', 2023, 'BOS'],
            ['BRG-003', 'Meja Siswa', 'Furnitur', $ruangModels[0]->id, 36, 'baik', null, 2020, 'APBD'],
            ['BRG-004', 'Kursi Siswa', 'Furnitur', $ruangModels[0]->id, 36, 'rusak_ringan', null, 2020, 'APBD'],
            ['BRG-005', 'Papan Tulis Whiteboard', 'Perlengkapan', $ruangModels[0]->id, 1, 'baik', null, 2021, 'BOS'],
            ['BRG-006', 'Komputer PC', 'Elektronik', $ruangModels[6]->id, 20, 'baik', 'Dell Optiplex', 2023, 'APBN'],
            ['BRG-007', 'Mikroskop', 'Alat Lab', $ruangModels[4]->id, 5, 'baik', 'Olympus CX23', 2022, 'BOS'],
            ['BRG-008', 'AC Split', 'Elektronik', $ruangModels[8]->id, 2, 'rusak_ringan', 'Panasonic', 2019, 'APBD'],
            ['BRG-009', 'Sofa Ruang Guru', 'Furnitur', $ruangModels[10]->id, 2, 'baik', null, 2021, 'BOS'],
            ['BRG-010', 'Printer', 'Elektronik', $ruangModels[11]->id, 1, 'baik', 'Canon Pixma', 2023, 'BOS'],
            ['BRG-011', 'Sound System', 'Elektronik', $ruangModels[9]->id, 1, 'baik', 'Yamaha', 2022, 'APBD'],
            ['BRG-012', 'Lemari Arsip', 'Furnitur', $ruangModels[11]->id, 3, 'baik', null, 2020, 'APBD'],
        ];
        $barangModels = [];
        foreach ($barangData as $b) {
            $barangModels[] = Barang::create([
                'kode' => $b[0],
                'nama' => $b[1],
                'kategori' => $b[2],
                'ruang_id' => $b[3],
                'jumlah' => $b[4],
                'kondisi' => $b[5],
                'merek' => $b[6],
                'tahun_peroleh' => $b[7],
                'sumber_dana' => $b[8],
            ]);
        }

        // Peminjaman Aset
        PeminjamanAset::create([
            'peminjam_type' => 'App\Models\User',
            'peminjam_id' => $guruUsers[0]->id,
            'ruang_id' => $ruangModels[8]->id,
            'tujuan' => 'Kegiatan LDKS',
            'tanggal_mulai' => now()->addDays(3),
            'tanggal_selesai' => now()->addDays(5),
            'status' => 'disetujui',
            'approved_by' => $admin->id,
        ]);
        PeminjamanAset::create([
            'peminjam_type' => 'App\Models\User',
            'peminjam_id' => $siswaUsers[0]->id,
            'barang_id' => $barangModels[0]->id,
            'tujuan' => 'Presentasi Tugas Akhir',
            'tanggal_mulai' => now()->addDay(),
            'tanggal_selesai' => now()->addDay(),
            'status' => 'diajukan',
        ]);
        PeminjamanAset::create([
            'peminjam_type' => 'App\Models\User',
            'peminjam_id' => $guruUsers[0]->id,
            'ruang_id' => $ruangModels[9]->id,
            'tujuan' => 'Rapat Orang Tua Murid',
            'tanggal_mulai' => now()->subDays(5),
            'tanggal_selesai' => now()->subDays(5),
            'status' => 'dikembalikan',
            'approved_by' => $admin->id,
        ]);

        // Maintenance Aset
        MaintenanceAset::create([
            'barang_id' => $barangModels[7]->id,
            'jenis' => 'perbaikan',
            'deskripsi' => 'AC tidak dingin, perlu pengecekan freon dan pembersihan filter',
            'tanggal_mulai' => today(),
            'status' => 'sedang_dikerjakan',
            'pelaksana' => 'Teknisi AC',
            'biaya' => 250000,
        ]);
        MaintenanceAset::create([
            'ruang_id' => $ruangModels[5]->id,
            'jenis' => 'perawatan',
            'deskripsi' => 'Pembersihan dan kalibrasi alat laboratorium secara berkala',
            'tanggal_mulai' => now()->addDays(7),
            'tanggal_selesai' => now()->addDays(8),
            'status' => 'direncanakan',
            'pelaksana' => 'Laboran',
        ]);
        MaintenanceAset::create([
            'barang_id' => $barangModels[3]->id,
            'jenis' => 'perbaikan',
            'deskripsi' => 'Perbaikan kursi siswa yang rusak ringan (engsel longgar)',
            'tanggal_mulai' => now()->subDays(10),
            'tanggal_selesai' => now()->subDays(8),
            'status' => 'selesai',
            'pelaksana' => 'Tukang Kayu',
            'biaya' => 50000,
        ]);
    }
}
