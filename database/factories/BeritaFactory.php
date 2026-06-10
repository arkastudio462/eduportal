<?php

namespace Database\Factories;

use App\Models\Berita;
use Illuminate\Database\Eloquent\Factories\Factory;

class BeritaFactory extends Factory
{
    protected $model = Berita::class;

    public function definition(): array
    {
        return [
            'judul' => fake()->sentence(6),
            'konten' => fake()->paragraphs(5, true),
            'kategori' => fake()->randomElement(['umum', 'akademik', 'kegiatan', 'prestasi']),
            'is_utama' => fake()->boolean(20),
            'tanggal' => fake()->dateTimeBetween('-3 months', 'now'),
        ];
    }
}
