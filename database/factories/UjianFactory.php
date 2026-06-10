<?php

namespace Database\Factories;

use App\Models\Mapel;
use App\Models\Ujian;
use Illuminate\Database\Eloquent\Factories\Factory;

class UjianFactory extends Factory
{
    protected $model = Ujian::class;

    public function definition(): array
    {
        return [
            'nama' => fake()->sentence(3),
            'mapel_id' => Mapel::factory(),
            'tanggal_mulai' => now()->addDays(7),
            'tanggal_selesai' => now()->addDays(8),
            'durasi' => fake()->randomElement([60, 90, 120]),
            'status' => fake()->randomElement(['draft', 'akan_datang']),
        ];
    }
}
