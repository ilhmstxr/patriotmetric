<?php

namespace Database\Factories;

use App\Models\Institusi;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Assessment>
 */
class AssessmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'institution_id' => Institusi::factory(),
            'user_id' => User::factory(),
            'nama_pic' => fake()->name(),
            'jabatan_pic' => fake()->jobTitle(),
            'no_hp_pic' => fake()->phoneNumber(),
            'tahun_periode' => fake()->year(),
            'status' => fake()->randomElement(['pending', 'verified', 'baseline_submitted', 'active']),
        ];
    }
}
