<?php

namespace Database\Seeders;

use App\Models\Identitas;
use App\Models\Pengumpulan;
use Illuminate\Database\Seeder;

class IdentitasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pengumpulans = Pengumpulan::all();

        if ($pengumpulans->count() === 0) {
            $pengumpulans = Pengumpulan::factory(5)->create();
        }

        foreach ($pengumpulans as $pengumpulan) {
            Identitas::factory()->create([
                'pengumpulan_id' => $pengumpulan->id,
            ]);
        }
    }
}
