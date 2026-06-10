<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // Global
            'nama_sekolah' => 'SMA Nusantara',
            'alamat' => 'Jl. Pendidikan No. 123, Jakarta Selatan, DKI Jakarta 12345',
            'telepon' => '(021) 555-0123',
            'email_sekolah' => 'info@smanusantara.sch.id',
            'website' => 'https://smanusantara.sch.id',
            'akreditasi' => 'A',
            'kepala_sekolah' => 'Drs. H. Ahmad Fauzi',
            'visi' => 'Menjadi lembaga pendidikan terkemuka yang menghasilkan lulusan unggul dalam prestasi, berkarakter mulia, dan berwawasan lingkungan menuju persaingan global.',
            'misi' => "Menyelenggarakan proses pembelajaran yang inovatif, kreatif, dan berbasis teknologi informasi.\nMenumbuhkembangkan budi pekerti luhur dan nilai-nilai keagamaan dalam seluruh aspek kehidupan sekolah.\nMengembangkan potensi bakat dan minat siswa melalui kegiatan ekstrakurikuler yang beragam.",
            'tentang' => 'SMA Nusantara adalah lembaga pendidikan menengah atas yang berkomitmen untuk mencetak generasi unggul dan berkarakter.',
            'tahun_ajaran' => '2024/2025',

            // Home
            'home_hero_title' => 'Selamat Datang di <br><span class="text-secondary-fixed">SMA Nusantara</span>',
            'home_hero_subtitle' => 'Membentuk Generasi Unggul yang Cerdas, Berkarakter, dan Berdaya Saing Global Melalui Pendidikan Berbasis Teknologi dan Kearifan Lokal.',
            'home_stats_siswa_label' => 'Total Siswa',
            'home_stats_guru_label' => 'Guru Ahli',
            'home_stats_ekskul_label' => 'Ekstrakurikuler',
            'home_stats_prestasi_label' => 'Prestasi',

            // Profil
            'profil_hero_title' => 'Profil SMA Nusantara',
            'profil_hero_subtitle' => 'Membangun generasi pemimpin masa depan dengan integritas tinggi, wawasan global, dan penguasaan teknologi yang adaptif.',

            // Akademik
            'akademik_hero_title' => 'Pusat Keunggulan Akademik',
            'akademik_hero_subtitle' => 'Membentuk generasi emas Indonesia melalui kurikulum berbasis karakter, teknologi digital, dan standar internasional yang holistik.',

            // Kontak
            'kontak_alamat' => 'Jl. Pendidikan No. 123, Jakarta Selatan, DKI Jakarta 12345',
            'kontak_telepon' => '(021) 555-0123',
            'kontak_email' => 'info@smanusantara.sch.id',
            'kontak_maps_url' => 'https://maps.google.com',
            'kontak_jam_senin_kamis' => '07:00 - 15:30',
            'kontak_jam_jumat' => '07:00 - 11:30',
            'kontak_jam_sabtu' => '08:00 - 12:00',

            // Berita
            'berita_hero_title' => 'Berita Terkini',
        ];

        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
