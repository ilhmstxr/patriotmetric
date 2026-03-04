<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeArchitectureCommand extends Command
{
    // Penggunaan: php artisan make:arch NamaModel
    protected $signature = 'make:arch {name}';
    protected $description = 'Generate DTO, Service, and Repository for a model';

    public function handle()
    {
        $name = $this->argument('name');

        // Daftar file yang akan di-generate beserta lokasinya
        $files = [
            'DTO' => 'app/DTOs',
            'Service' => 'app/Services',
            'Repository' => 'app/Repositories',
            'Resource' => 'app/Http/Resources',
        ];

        foreach ($files as $suffix => $path) {
            $this->generateFromStub($name, $suffix, $path);
        }
        $this->info("🚀 Arsitektur untuk {$name} berhasil dibuat!");
    }
    /**
     * Fungsi untuk membaca stub, mengganti placeholder, dan menyimpan file.
     */
    protected function generateFromStub($name, $suffix, $path)
    {
        $className = "{$name}{$suffix}";
        $directory = base_path($path);
        $stubName = strtolower($suffix) . ".stub"; // Contoh: dto.stub
        $stubPath = base_path("stubs/{$stubName}");

        // 1. Pastikan folder tujuan ada
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        // 2. Pastikan file stub ada di folder stubs
        if (!File::exists($stubPath)) {
            $this->error("❌ File stub tidak ditemukan: stubs/{$stubName}");
            return;
        }

        $targetPath = "{$directory}/{$className}.php";

        // 3. Cek apakah file sudah pernah dibuat sebelumnya
        if (File::exists($targetPath)) {
            $this->warn("⚠️  File {$className}.php sudah ada, melewati...");
            return;
        }

        // 4. Baca isi stub dan ganti placeholder {{name}}
        $content = File::get($stubPath);
        $content = str_replace('{{name}}', $name, $content);

        // 5. Simpan file baru
        File::put($targetPath, $content);
        $this->line("✅ Berhasil dibuat: {$path}/{$className}.php");
    }
}