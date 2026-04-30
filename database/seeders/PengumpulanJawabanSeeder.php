<?php

namespace Database\Seeders;

use App\Models\PengumpulanJawaban;
use App\Models\Pertanyaan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PengumpulanJawabanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = \App\Models\User::where('email', 'peserta@test.com')->first();
        if (!$user) return;
        $submission = \App\Models\Pengumpulan::where('user_id', $user->id)->first();
        if (!$submission) return;
        $submissionId = $submission->id;
        $pertanyaans = Pertanyaan::all();

        foreach ($pertanyaans as $pertanyaan) {
            $jawabanId = null;
            $jawabanTeks = null;

            if ($pertanyaan->tipe === 'pilihan_ganda') {
                $opsi = $pertanyaan->opsiJawaban()->inRandomOrder()->first();
                $jawabanId = $opsi ? $opsi->id : null;
            } else {
                $jawabanTeks = 'Jawaban simulasi untuk pertanyaan: ' . $pertanyaan->kode_pertanyaan;
            }

            // Random URL for tautan_bukti_drive (can be null)
            $url = null;
            if (rand(0, 1)) {
                $url = 'https://drive.google.com/file/d/' . Str::random(32) . '/view?usp=sharing';
            }

            PengumpulanJawaban::updateOrCreate(
                [
                    'submission_id' => $submissionId,
                    'pertanyaan_id' => $pertanyaan->id,
                ],
                [
                    'jawaban_id' => $jawabanId,
                    'jawaban_teks' => $jawabanTeks,
                    'tautan_bukti_drive' => $url,
                    'skor_sistem' => rand(0, 5), // Optional: random score for simulation
                    'skor_validasi_reviewer' => 0,
                ]
            );
        }
    }
}
