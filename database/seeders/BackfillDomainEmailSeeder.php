<?php

namespace Database\Seeders;

use App\Models\Institusi;
use App\Models\Pengumpulan;
use Illuminate\Database\Seeder;

/**
 * Mengisi kolom domain_email pada institusi yang sudah ada
 * berdasarkan email PIC pertama. Idempotent.
 */
class BackfillDomainEmailSeeder extends Seeder
{
    public function run(): void
    {
        $count = 0;
        Institusi::whereNull('domain_email')->orWhere('domain_email', '')->chunk(50, function ($items) use (&$count) {
            foreach ($items as $inst) {
                $pengumpulan = Pengumpulan::with('user')->where('institution_id', $inst->id)->first();
                $email = $pengumpulan?->user?->email;
                if ($email && str_contains($email, '@')) {
                    $inst->domain_email = strtolower(substr(strrchr($email, '@'), 1));
                    $inst->save();
                    $count++;
                }
            }
        });

        $this->command->info("Backfilled domain_email untuk {$count} institusi.");
    }
}
