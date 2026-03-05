<?php

namespace Database\Seeders;

use App\Models\pengumpulanJawaban;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PengumpulanJawabanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        pengumpulanJawaban::factory(30)->create();
    }
}
