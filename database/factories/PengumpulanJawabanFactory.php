<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Pengumpulan;
use App\Models\PengumpulanJawaban;
use App\Models\Pertanyaan;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PengumpulanJawaban>
 */
class PengumpulanJawabanFactory extends Factory
{
    protected $model = PengumpulanJawaban::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'submission_id' => Pengumpulan::factory(),
            'question_id' => Pertanyaan::factory(),
            'jawaban_teks' => $this->faker->paragraph(),
            'tautan_bukti_drive' => $this->faker->url(),
            'skor_sistem' => $this->faker->randomFloat(2, 0, 100),
            'skor_validasi_reviewer' => $this->faker->randomFloat(2, 0, 100),
        ];
    }
}
