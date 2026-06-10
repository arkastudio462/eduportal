<?php

namespace Database\Seeders;

use App\Models\Barang;
use App\Models\MaintenanceAset;
use App\Models\PeminjamanAset;
use App\Models\Ruang;
use App\Models\User;
use Illuminate\Database\Seeder;

class AsetInventarisSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();
        $guru = User::where('role', 'guru')->first();
        $siswa = User::where('role', 'siswa')->first();

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
                'kode' => $r[0], 'nama' => $r[1], 'lantai' => $r[2], 'gedung' => $r[3],
                'kapasitas' => $r[4], 'jenis' => $r[5], 'status' => $r[6],
            ]);
        }
        $this->command->info('Created '.count($ruangModels).' rooms.');

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
                'kode' => $b[0], 'nama' => $b[1], 'kategori' => $b[2], 'ruang_id' => $b[3],
                'jumlah' => $b[4], 'kondisi' => $b[5], 'merek' => $b[6], 'tahun_peroleh' => $b[7],
                'sumber_dana' => $b[8],
            ]);
        }
        $this->command->info('Created '.count($barangModels).' items.');

        PeminjamanAset::create([
            'peminjam_type' => 'App\Models\User', 'peminjam_id' => $guru->id,
            'ruang_id' => $ruangModels[8]->id, 'tujuan' => 'Kegiatan LDKS',
            'tanggal_mulai' => now()->addDays(3), 'tanggal_selesai' => now()->addDays(5),
            'status' => 'disetujui', 'approved_by' => $admin->id,
        ]);
        PeminjamanAset::create([
            'peminjam_type' => 'App\Models\User', 'peminjam_id' => $siswa->id,
            'barang_id' => $barangModels[0]->id, 'tujuan' => 'Presentasi Tugas Akhir',
            'tanggal_mulai' => now()->addDay(), 'tanggal_selesai' => now()->addDay(),
            'status' => 'diajukan',
        ]);
        PeminjamanAset::create([
            'peminjam_type' => 'App\Models\User', 'peminjam_id' => $guru->id,
            'ruang_id' => $ruangModels[9]->id, 'tujuan' => 'Rapat Orang Tua Murid',
            'tanggal_mulai' => now()->subDays(5), 'tanggal_selesai' => now()->subDays(5),
            'status' => 'dikembalikan', 'approved_by' => $admin->id,
        ]);
        $this->command->info('Created peminjaman aset.');

        MaintenanceAset::create([
            'barang_id' => $barangModels[7]->id, 'jenis' => 'perbaikan',
            'deskripsi' => 'AC tidak dingin, perlu pengecekan freon dan pembersihan filter',
            'tanggal_mulai' => today(), 'status' => 'sedang_dikerjakan',
            'pelaksana' => 'Teknisi AC', 'biaya' => 250000,
        ]);
        MaintenanceAset::create([
            'ruang_id' => $ruangModels[5]->id, 'jenis' => 'perawatan',
            'deskripsi' => 'Pembersihan dan kalibrasi alat laboratorium secara berkala',
            'tanggal_mulai' => now()->addDays(7), 'tanggal_selesai' => now()->addDays(8),
            'status' => 'direncanakan', 'pelaksana' => 'Laboran',
        ]);
        MaintenanceAset::create([
            'barang_id' => $barangModels[3]->id, 'jenis' => 'perbaikan',
            'deskripsi' => 'Perbaikan kursi siswa yang rusak ringan (engsel longgar)',
            'tanggal_mulai' => now()->subDays(10), 'tanggal_selesai' => now()->subDays(8),
            'status' => 'selesai', 'pelaksana' => 'Tukang Kayu', 'biaya' => 50000,
        ]);
        $this->command->info('Created maintenance aset.');
    }
}
