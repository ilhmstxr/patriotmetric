<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\PengaturanCms;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PengaturanCms>
 */
class PengaturanCmsFactory extends Factory
{
    protected $model = PengaturanCms::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'key' => $this->faker->unique()->word(),
            'value' => $this->faker->sentence(),
        ];
    }
}
