<?php

namespace Database\Factories;

use App\Models\Jurusan;
use App\Models\Kelas;
use Illuminate\Database\Eloquent\Factories\Factory;

class KelasFactory extends Factory
{
    protected $model = Kelas::class;

    public function definition(): array
    {
        $tingkat = fake()->randomElement(['X', 'XI', 'XII']);
        $jurusan = fake()->randomElement(['IPA', 'IPS', 'Bahasa']);

        return [
            'nama' => "{$tingkat} {$jurusan} ".fake()->randomDigitNotNull(),
            'tingkat' => $tingkat,
            'jurusan_id' => Jurusan::factory(),
        ];
    }
}
