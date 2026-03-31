<?php

namespace Database\Seeders;

use App\Models\kategori;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // kategori::factory(10)->create();
        $kategori = [
            [
                'nama_kategori' => 'A. VARIABEL PATRIOTISME KEBIJAKAN',
            ],
            [
                'nama_kategori' => 'B. VARIABEL PATRIOTISME KELEMBAGAAN',
            ],
            [
                'nama_kategori' => 'C. VARIABEL PATRIOTISME MAHASISWA',
            ],
        ];

        kategori::insert($kategori);
    }
}
