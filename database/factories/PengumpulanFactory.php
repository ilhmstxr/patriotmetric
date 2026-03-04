<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\pengumpulan;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\pengumpulan>
 */
class PengumpulanFactory extends Factory
{
    protected $model = pengumpulan::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'reviewer_id' => User::factory(),
            'status' => $this->faker->randomElement(['pending', 'approved', 'rejected']),
            'total_skor_sistem' => $this->faker->randomFloat(2, 0, 100),
            'total_skor_akhir' => $this->faker->randomFloat(2, 0, 100),
        ];
    }
}
