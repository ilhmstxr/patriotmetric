<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\kategori;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\kategori>
 */
class KategoriFactory extends Factory
{
    protected $model = kategori::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama_kategori' => $this->faker->words(3, true),
            'deskripsi' => $this->faker->paragraph(),
            'bobot_presentase' => $this->faker->randomFloat(2, 0, 100),
        ];
    }
}
