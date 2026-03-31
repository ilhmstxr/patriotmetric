<?php

namespace Database\Seeders;

use App\Models\Assessment;
use App\Models\Identitas;
use Illuminate\Database\Seeder;

class IdentitasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $assessments = Assessment::all();

        if ($assessments->count() === 0) {
            $assessments = Assessment::factory(5)->create();
        }

        foreach ($assessments as $assessment) {
            Identitas::factory()->create([
                'assessment_id' => $assessment->id,
            ]);
        }
    }
}
