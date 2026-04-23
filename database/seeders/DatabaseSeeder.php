<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        User::create([
            'email' => "admin@admin.com",
            'password' => bcrypt('admin'),
            'role' => 'ADMIN',
            'status' => 'ACTIVE',
        ]);

        User::create([
            'email' => "user1@admin.com",
            'password' => bcrypt('user'),
            'role' => 'PESERTA',
            'status' => 'ACTIVE',
        ]);

        User::create([
            'email' => "user2@admin.com",
            'password' => bcrypt('user'),
            'role' => 'PESERTA',
            'status' => 'ACTIVE',
        ]);
        User::create([
            'email' => "user3@admin.com",
            'password' => bcrypt('user'),
            'role' => 'PESERTA',
            'status' => 'ACTIVE',
        ]);

        User::create([
            'email' => "user4@admin.com",
            'password' => bcrypt('user'),
            'role' => 'PESERTA',
            'status' => 'ACTIVE',
        ]);
        User::create([
            'email' => "user5@admin.com",
            'password' => bcrypt('user'),
            'role' => 'PESERTA',
            'status' => 'ACTIVE',
        ]);

        User::create([
            'email' => "user6@admin.com",
            'password' => bcrypt('user'),
            'role' => 'PESERTA',
            'status' => 'ACTIVE',
        ]);

        User::create([
            'email' => "reviewer@admin.com",
            'password' => bcrypt('reviewer'),
            'role' => 'REVIEWER',
            'status' => 'ACTIVE',
        ]);

        $this->call([
            InstitusiSeeder::class,
            // AssessmentSeeder::class,
            PengumpulanSeeder::class,
            // IdentitasSeeder::class,

            KategoriSeeder::class,
            PertanyaanSeeder::class,
            PengumpulanJawabanSeeder::class,
            PengaturanCmsSeeder::class,
        ]);




        $this->call([
            // KategoriSeeder::class,
            // PertanyaanSeeder::class,
            // PengumpulanSeeder::class,
            // PengumpulanJawabanSeeder::class,
            // PengaturanCmsSeeder::class,

        ]);
    }
}
