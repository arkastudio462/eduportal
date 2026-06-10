<?php

namespace Database\Seeders;

use App\Models\Pendaftaran;
use Illuminate\Database\Seeder;

class PpdbSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'no_pendaftaran' => 'PPDB-20260610-ABC01',
                'nama_lengkap' => 'Ahmad Rizki Pratama',
                'nisn' => '0012345671',
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir' => '2008-05-12',
                'jenis_kelamin' => 'L',
                'agama' => 'Islam',
                'alamat' => 'Jl. Merdeka No. 45, Jakarta Pusat',
                'no_hp' => '081234567890',
                'email' => 'ahmadrizki@example.com',
                'asal_sekolah' => 'SMP Negeri 1 Jakarta',
                'jurusan_daftar' => 'IPA',
                'nilai_rata_rata' => '88.5',
                'nama_ayah' => 'Budi Santoso',
                'nama_ibu' => 'Siti Rahmawati',
                'no_hp_ayah' => '081298765432',
                'no_hp_ibu' => '087812345678',
                'pekerjaan_ayah' => 'PNS',
                'pekerjaan_ibu' => 'Ibu Rumah Tangga',
                'status' => 'menunggu',
            ],
            [
                'no_pendaftaran' => 'PPDB-20260610-ABC02',
                'nama_lengkap' => 'Dewi Lestari',
                'nisn' => '0012345672',
                'tempat_lahir' => 'Bandung',
                'tanggal_lahir' => '2008-08-22',
                'jenis_kelamin' => 'P',
                'agama' => 'Islam',
                'alamat' => 'Jl. Asia Afrika No. 10, Bandung',
                'no_hp' => '082134567890',
                'email' => 'dewilestari@example.com',
                'asal_sekolah' => 'SMP Negeri 2 Bandung',
                'jurusan_daftar' => 'IPA',
                'nilai_rata_rata' => '91.2',
                'nama_ayah' => 'Hendra Gunawan',
                'nama_ibu' => 'Ratna Dewi',
                'no_hp_ayah' => '081312345678',
                'no_hp_ibu' => '085612345678',
                'pekerjaan_ayah' => 'Wiraswasta',
                'pekerjaan_ibu' => 'Guru',
                'status' => 'diverifikasi',
            ],
            [
                'no_pendaftaran' => 'PPDB-20260610-ABC03',
                'nama_lengkap' => 'Bagas Putra Wijaya',
                'nisn' => '0012345673',
                'tempat_lahir' => 'Surabaya',
                'tanggal_lahir' => '2008-01-15',
                'jenis_kelamin' => 'L',
                'agama' => 'Islam',
                'alamat' => 'Jl. Tunjungan No. 25, Surabaya',
                'no_hp' => '083145678901',
                'email' => 'bagaspw@example.com',
                'asal_sekolah' => 'SMP Negeri 3 Surabaya',
                'jurusan_daftar' => 'IPS',
                'nilai_rata_rata' => '82.0',
                'nama_ayah' => 'Agus Wijaya',
                'nama_ibu' => 'Sari Indah',
                'no_hp_ayah' => '081345678901',
                'no_hp_ibu' => '085734567890',
                'pekerjaan_ayah' => 'Karyawan Swasta',
                'pekerjaan_ibu' => 'Ibu Rumah Tangga',
                'status' => 'diterima',
                'catatan' => 'Selamat! Anda diterima di jurusan IPS.',
            ],
            [
                'no_pendaftaran' => 'PPDB-20260610-ABC04',
                'nama_lengkap' => 'Citra Ayu Ningsih',
                'nisn' => '0012345674',
                'tempat_lahir' => 'Yogyakarta',
                'tanggal_lahir' => '2008-11-03',
                'jenis_kelamin' => 'P',
                'agama' => 'Islam',
                'alamat' => 'Jl. Malioboro No. 78, Yogyakarta',
                'no_hp' => '085678901234',
                'email' => 'citraayu@example.com',
                'asal_sekolah' => 'SMP Negeri 4 Yogyakarta',
                'jurusan_daftar' => 'IPA',
                'nilai_rata_rata' => '76.5',
                'nama_ayah' => 'Suprapto',
                'nama_ibu' => 'Wahyuningsih',
                'no_hp_ayah' => '081456789012',
                'no_hp_ibu' => '087856789012',
                'pekerjaan_ayah' => 'Petani',
                'pekerjaan_ibu' => 'Pedagang',
                'status' => 'ditolak',
                'catatan' => 'Maaf, nilai rata-rata tidak memenuhi syarat minimum.',
            ],
            [
                'no_pendaftaran' => 'PPDB-20260610-ABC05',
                'nama_lengkap' => 'Eko Prasetyo',
                'nisn' => '0012345675',
                'tempat_lahir' => 'Semarang',
                'tanggal_lahir' => '2008-03-27',
                'jenis_kelamin' => 'L',
                'agama' => 'Kristen',
                'alamat' => 'Jl. Pandanaran No. 12, Semarang',
                'no_hp' => '089012345678',
                'email' => 'ekopras@example.com',
                'asal_sekolah' => 'SMP Negeri 5 Semarang',
                'jurusan_daftar' => 'IPS',
                'nilai_rata_rata' => '85.0',
                'nama_ayah' => 'Yohanes Prasetyo',
                'nama_ibu' => 'Maria Susanti',
                'no_hp_ayah' => '081567890123',
                'no_hp_ibu' => '085967890123',
                'pekerjaan_ayah' => 'Dosen',
                'pekerjaan_ibu' => 'Perawat',
                'status' => 'menunggu',
            ],
        ];

        foreach ($data as $d) {
            Pendaftaran::create($d);
        }

        $this->command->info('Created '.count($data).' PPDB registrations.');
    }
}
