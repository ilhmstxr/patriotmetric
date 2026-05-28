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
        PengaturanCms::updateOrCreate(
            ['key' => 'active_period'],
            ['value' => date('Y')]
        );
        PengaturanCms::updateOrCreate(
            ['key' => 'is_peserta_edit_enabled'],
            ['value' => 'true']
        );
    }
}
