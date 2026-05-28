<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReviewerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = \App\Models\User::where('email', 'reviewer@admin.com')->first();
        if ($user) {
            \App\Models\Reviewer::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'nama_lengkap' => 'Reviewer Testing',
                    'nip' => '198001012000031001'
                ]
            );
        }
    }
}
