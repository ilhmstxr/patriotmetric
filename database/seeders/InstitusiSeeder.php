<?php

namespace Database\Seeders;

use App\Models\Institusi;
use Illuminate\Database\Seeder;

class InstitusiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $institusiNames = [
            'Universitas Indonesia',
            'Institut Teknologi Bandung',
            'Universitas Gadjah Mada',
            'Universitas Airlangga',
            'Institut Pertanian Bogor'
        ];

        foreach ($institusiNames as $name) {
            Institusi::factory()->create([
                'nama_institusi' => $name,
            ]);
        }
    }
}
