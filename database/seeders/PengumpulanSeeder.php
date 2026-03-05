<?php

namespace Database\Seeders;

use App\Models\pengumpulan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PengumpulanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        pengumpulan::factory(15)->create();
    }
}
