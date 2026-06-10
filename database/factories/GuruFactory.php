<?php

namespace Database\Factories;

use App\Models\Guru;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class GuruFactory extends Factory
{
    protected $model = Guru::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'nuptk' => fake()->unique()->numerify('##########'),
            'nip' => fake()->unique()->numerify('##########'),
            'mata_pelajaran' => fake()->randomElement([
                'Matematika', 'Fisika', 'Kimia', 'Biologi',
                'Bahasa Indonesia', 'Bahasa Inggris', 'Sejarah',
            ]),
        ];
    }
}
