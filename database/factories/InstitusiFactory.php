<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Institusi>
 */
class InstitusiFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => fake()->uuid(),
            'nama_institusi' => fake()->company() . ' University',
            'jenis_institusi' => fake()->randomElement(['PTN', 'PTS', 'PTK']),
        ];
    }
}
