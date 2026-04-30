<?php

namespace Database\Seeders;

use App\Models\Institusi;
use App\Models\Pengumpulan;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PengumpulanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pengumpulan::factory(15)->create();

        $emails = [
            'peserta@test.com',
        ];

        $users = User::whereIn('email', $emails)->get();
        $institusis = Institusi::all();

        if ($institusis->isEmpty()) {
            $institusis = Institusi::factory(5)->create();
        }

        foreach ($users as $index => $user) {
            // Check if we still have unique institutions available to avoid unique constraint error
            if ($index >= $institusis->count()) {
                break;
            }

            $institusi = $institusis->get($index);

            Pengumpulan::create([
                'user_id' => $user->id,
                'institution_id' => $institusi->id,
                'nama_pic' => 'PIC Default',
                'jabatan_pic' => null,
                'no_hp_pic' => '081234567890',
                'tahun_periode' => 2026,
                'status' => 'IN_PROGRESS',
                'total_skor_sistem' => 0,
                'total_skor_akhir' => 0,
                'reviewer_id' => null
            ]);
        }
    }
}
