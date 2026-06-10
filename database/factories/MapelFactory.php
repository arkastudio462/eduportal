<?php

namespace Database\Factories;

use App\Models\Mapel;
use Illuminate\Database\Eloquent\Factories\Factory;

class MapelFactory extends Factory
{
    protected $model = Mapel::class;

    public function definition(): array
    {
        $mapel = fake()->unique()->randomElement([
            'Matematika', 'Fisika', 'Kimia', 'Biologi',
            'Bahasa Indonesia', 'Bahasa Inggris', 'Sejarah',
            'Geografi', 'Ekonomi', 'Sosiologi',
            'Seni Budaya', 'Pendidikan Agama', 'PKN', 'Olahraga',
        ]);

        return [
            'nama' => $mapel,
            'kode' => strtoupper(substr($mapel, 0, 3)),
        ];
    }
}
