<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\pengumpulan;
use App\Models\pengumpulanJawaban;
use App\Models\pertanyaan;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\pengumpulan_jawaban>
 */
class PengumpulanJawabanFactory extends Factory
{
    protected $model = pengumpulanJawaban::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'submission_id' => pengumpulan::factory(),
            'question_id' => pertanyaan::factory(),
            'jawaban_teks' => $this->faker->paragraph(),
            'tautan_bukti_drive' => $this->faker->url(),
            'skor_sistem' => $this->faker->randomFloat(2, 0, 100),
            'skor_validasi_reviewer' => $this->faker->randomFloat(2, 0, 100),
        ];
    }
}
