<?php

namespace Database\Seeders;

use App\Models\pertanyaan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PertanyaanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // pertanyaan::factory(20)->create();
        $pertanyaan = [
            [
                'category_id' => 1,
                'teks_pertanyaan' => 'A.1. Kebijakan Implementasi Nilai-Nilai Bela Negara dalam Kegiatan Tridharma Pilihan Jawaban',
                'tipe' => 'pilihan_ganda',
                'opsi_jawaban' => '[
                "{
                    "urutan" => 0,
                    "teks_jawaban" => "Tidak ada",
                }", 
                "{
                    "urutan" => 1,
                    "teks_jawaban" => "Ada kebijakan tertulis tetapi belum diimplementasikan",
                }", 
                "{
                    "urutan" => 2,
                    "teks_jawaban" => "Ada kebijakan dan diimplementasikan dalam satu kegiatan dari Tridharma",
                }", 
                "{
                    "urutan" => 3,
                    "teks_jawaban" => "Ada kebijakan dan diimplementasikan dalam dua kegiatan dari Tridharma",
                }", 
                "{
                    "urutan" => 4,
                    "teks_jawaban" => "Ada kebijakan dan diimplementasikan dalam seluruh kegiatan dari Tridharma",
                }", 
                "{
                    "urutan" => 5,
                    "teks_jawaban" => "Ada kebijakan dan diimplementasikan dalam seluruh kegiatan Tridharma serta kegiatan penunjang",
                }", 
                ]',
            ],
        ];

        pertanyaan::create($pertanyaan);
    }
}
