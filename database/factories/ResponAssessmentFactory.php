<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Assessment;
use App\Models\ResponAssessment;
use App\Models\Pertanyaan;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ResponAssessment>
 */
class ResponAssessmentFactory extends Factory
{
    protected $model = ResponAssessment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'assessment_id' => Assessment::factory(),
            'question_id' => Pertanyaan::factory(),
            'jawaban_teks' => $this->faker->paragraph(),
            'tautan_bukti_drive' => $this->faker->url(),
            'skor_sistem' => $this->faker->randomFloat(2, 0, 100),
            'skor_validasi_reviewer' => $this->faker->randomFloat(2, 0, 100),
        ];
    }
}
