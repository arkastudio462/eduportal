<?php

namespace Database\Factories;

use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\Mapel;
use Illuminate\Database\Eloquent\Factories\Factory;

class JadwalFactory extends Factory
{
    protected $model = Jadwal::class;

    public function definition(): array
    {
        return [
            'kelas_id' => Kelas::factory(),
            'mapel_id' => Mapel::factory(),
            'guru_id' => Guru::factory(),
            'hari' => fake()->randomElement(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat']),
            'jam_mulai' => fake()->randomElement(['07:00', '08:00', '09:00', '10:00']),
            'jam_selesai' => fake()->randomElement(['08:00', '09:00', '10:00', '11:00']),
            'ruang' => fake()->randomElement(['R-101', 'R-102', 'Lab A', 'Lab B']),
        ];
    }
}
