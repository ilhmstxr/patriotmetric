<?php

namespace Database\Seeders;

use App\Models\Institusi;
use App\Models\pengumpulan;
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
        // pengumpulan::factory(15)->create();

        $emails = [
            'user1@admin.com',
            'user2@admin.com',
            'user3@admin.com',
            'user4@admin.com',
            'user5@admin.com',
            'user6@admin.com',
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

            pengumpulan::create([
                'user_id' => $user->id,
                'institution_id' => $institusi->id,
                'nama_pic' => 'PIC Default',
                'jabatan_pic' => null,
                'no_hp_pic' => '081234567890',
                'tahun_periode' => 2026,
                'status' => 'ACTIVE',
                'total_skor_sistem' => 0,
                'total_skor_akhir' => 0,
                'reviewer_id' => 8
            ]);
        }
    }
}
