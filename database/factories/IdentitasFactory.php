<?php

namespace Database\Factories;

use App\Models\Assessment;
use App\Models\pengumpulan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Identitas>
 */
class IdentitasFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'pengumpulan_id' => pengumpulan::factory(),
            'jml_mahasiswa' => fake()->numberBetween(1000, 50000),
            'jml_dosen' => fake()->numberBetween(100, 2000),
            'jml_tendik' => fake()->numberBetween(50, 1000),
            'jml_prodi' => fake()->numberBetween(10, 100),
            'jml_ukm' => fake()->numberBetween(5, 50),
            'legal_documents' => [
                'sk_rektor' => fake()->url(),
                'aipt' => fake()->url(),
            ],
            'is_verified' => fake()->boolean(),
            'admin_note' => fake()->optional()->sentence(),
        ];
    }
}
