<?php

namespace Database\Seeders;

use App\Models\Berita;
use Illuminate\Database\Seeder;

class BeritaSeeder extends Seeder
{
    public function run(): void
    {
        Berita::create([
            'judul' => 'Selamat Datang Tahun Ajaran Baru 2026/2027',
            'konten' => "Kami dengan bangga menyambut tahun ajaran baru 2026/2027. Semoga seluruh siswa dapat mengikuti kegiatan belajar mengajar dengan semangat dan antusiasme yang tinggi.\n\nBerbagai program dan kegiatan baru telah disiapkan untuk meningkatkan kualitas pendidikan di SMA Nusantara. Kami berharap kerjasama antara sekolah, siswa, dan orang tua dapat terus terjalin dengan baik.",
            'kategori' => 'umum',
            'is_utama' => true,
            'tanggal' => now()->subDays(5),
        ]);

        Berita::create([
            'judul' => 'Pendaftaran OSN Tingkat Kota 2026 Telah Dibuka',
            'konten' => "Olimpiade Sains Nasional (OSN) tingkat kota tahun 2026 telah resmi dibuka. Siswa-siswi SMA Nusantara diundang untuk berpartisipasi dalam ajang bergengsi ini.\n\nBidang yang dilombakan meliputi Matematika, Fisika, Kimia, Biologi, dan Astronomi. Pendaftaran dibuka hingga 15 Juni 2026. Silakan hubungi guru pembimbing masing-masing untuk informasi lebih lanjut.",
            'kategori' => 'akademik',
            'is_utama' => false,
            'tanggal' => now()->subDays(3),
        ]);

        Berita::create([
            'judul' => 'Kegiatan Bakti Sosial SMA Nusantara',
            'konten' => "SMA Nusantara kembali mengadakan kegiatan bakti sosial yang berlokasi di Desa Sukamaju. Kegiatan ini diikuti oleh seluruh siswa kelas XI dan didampingi oleh para guru.\n\nAcara meliputi pembagian sembako, pengobatan gratis, dan kegiatan belajar mengajar untuk anak-anak di desa tersebut. Semoga kegiatan ini dapat bermanfaat bagi masyarakat sekitar.",
            'kategori' => 'kegiatan',
            'is_utama' => false,
            'tanggal' => now()->subDays(10),
        ]);

        Berita::create([
            'judul' => 'Tim Basket SMA Nusantara Juara 1 Turnamen Antar Sekolah',
            'konten' => "Tim basket SMA Nusantara berhasil meraih juara 1 dalam turnamen antar sekolah se-Kota Bandung yang diselenggarakan di GOR Sabilulungan.\n\nPertandingan final berlangsung sengit melawan SMA Negeri 5 Bandung. Dengan skor akhir 68-62, tim basket SMA Nusantara berhasil membawa pulang piala bergilir dan hadiah sebesar Rp5.000.000.",
            'kategori' => 'prestasi',
            'is_utama' => true,
            'tanggal' => now()->subDays(7),
        ]);

        Berita::create([
            'judul' => 'Workshop Karier: Persiapan Masuk Perguruan Tinggi',
            'konten' => "SMA Nusantara menyelenggarakan workshop karier yang bertema 'Persiapan Masuk Perguruan Tinggi Negeri' untuk siswa kelas XII. Workshop ini menghadirkan narasumber dari berbagai universitas ternama.\n\nMateri yang disampaikan meliputi tips memilih jurusan, persiapan SNBT, dan strategi meraih beasiswa. Acara berlangsung di Aula SMA Nusantara dan dihadiri oleh 150 siswa.",
            'kategori' => 'akademik',
            'is_utama' => false,
            'tanggal' => now()->subDays(14),
        ]);

        Berita::factory(5)->create();
    }
}
