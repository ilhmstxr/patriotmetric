<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Pengumpulan;
use App\Models\User;
use App\Models\Institusi;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pengumpulan>
 */
class PengumpulanFactory extends Factory
{
    protected $model = Pengumpulan::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = $this->faker->randomElement(['PENDING', 'PENDING_BASELINE', 'ACTIVE', 'IN_PROGRESS', 'SUBMITTED', 'GRADED']);

        return [
            'institution_id' => Institusi::factory(),
            'nama_pic' => $this->faker->name(),
            'jabatan_pic' => $this->faker->jobTitle(),
            'no_hp_pic' => $this->faker->phoneNumber(),
            'tahun_periode' => $this->faker->year(),
            'user_id' => User::factory(),
            'reviewer_id' => in_array($status, ['GRADED', 'IN_PROGRESS', 'SUBMITTED']) ? User::factory() : null,
            'status' => $status,
            'total_skor_sistem' => $this->faker->randomFloat(2, 0, 100),
            'total_skor_akhir' => $status === 'GRADED' ? $this->faker->randomFloat(2, 0, 100) : 0,
        ];
    }
}
