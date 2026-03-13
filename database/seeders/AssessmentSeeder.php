<?php

namespace Database\Seeders;

use App\Models\Assessment;
use App\Models\Institusi;
use App\Models\User;
use Illuminate\Database\Seeder;

class AssessmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $institusi = Institusi::first() ?? Institusi::factory()->create();
        $user = User::where('role', 'SUBMITTER')->first() ?? User::factory()->create(['role' => 'SUBMITTER']);

        Assessment::factory()->create([
            'institution_id' => $institusi->id,
            'user_id' => $user->id,
            'tahun_periode' => date('Y'),
        ]);

        // Generate several random assessments
        Assessment::factory(5)->create();
    }
}
