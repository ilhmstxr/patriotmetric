<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\pertanyaan;
use App\Models\kategori;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\pertanyaan>
 */
class PertanyaanFactory extends Factory
{
    protected $model = pertanyaan::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category_id' => kategori::factory(),
            'teks_pertanyaan' => $this->faker->sentence(),
            'tipe' => $this->faker->randomElement(['pilihan_ganda', 'essay', 'file']),
            'opsi_jawaban' => $this->faker->randomElements(['A', 'B', 'C', 'D'], 4),
        ];
    }
}
