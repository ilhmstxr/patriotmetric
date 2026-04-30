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
        $emails = [
            'peserta@test.com',
        ];

        foreach ($emails as $email) {
            $user = \App\Models\User::where('email', $email)->first();
            if (!$user) continue;

            $pengumpulan = \App\Models\Pengumpulan::where('user_id', $user->id)->first();
            if (!$pengumpulan) continue;

            // Seed Identitas
            $identitas = \App\Models\Identitas::updateOrCreate(
                ['pengumpulan_id' => $pengumpulan->id],
                [
                    'jml_mahasiswa' => rand(5000, 20000),
                    'jml_dosen' => rand(200, 1000),
                    'jml_tendik' => rand(100, 500),
                    'jml_prodi' => rand(20, 100),
                    'jml_ukm' => rand(10, 50),
                    'jml_fakultas' => rand(5, 15),
                    'visi' => 'Menjadi institusi unggul dan berdaya saing global.',
                    'misi' => 'Menyelenggarakan pendidikan berkualitas dan penelitian inovatif.',
                    'is_verified' => true,
                ]
            );

            // Seed Agama
            $agamas = ['islam', 'kristen', 'katolik', 'hindu', 'buddha', 'konghucu', 'Kepercayaan Terhadap Tuhan Yang Maha Esa'];
            foreach ($agamas as $agama) {
                \App\Models\Agama::updateOrCreate(
                    [
                        'identitas_id' => $identitas->id,
                        'agama' => $agama
                    ],
                    [
                        'jumlah' => rand(0, 5000)
                    ]
                );
            }
        }
    }
}
