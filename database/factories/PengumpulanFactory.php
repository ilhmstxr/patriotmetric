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
        $status = $this->faker->randomElement(['pending', 'verified']);

        return [
            'user_id' => User::factory(),
            'reviewer_id' => $status === 'verified' ? User::factory() : null,
            'status' => $status,
            'total_skor_sistem' => $this->faker->randomFloat(2, 0, 100),
            'total_skor_akhir' => $status === 'verified' ? $this->faker->randomFloat(2, 0, 100) : 0,
        ];
    }
}
