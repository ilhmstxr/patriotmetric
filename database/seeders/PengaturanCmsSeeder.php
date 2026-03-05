<?php

namespace Database\Seeders;

use App\Models\PengaturanCms;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PengaturanCmsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PengaturanCms::factory(5)->create();
    }
}
