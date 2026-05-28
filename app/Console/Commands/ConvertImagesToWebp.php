<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ConvertImagesToWebp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'images:convert-webp 
                            {--path= : Path relatif dari public folder (contoh: assets/images)} 
                            {--quality=80 : Kualitas WebP (0-100)}
                            {--force : Overwrite file WebP yang sudah ada}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Konversi semua gambar (JPG, PNG, GIF) ke format WebP';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $basePath = public_path($this->option('path') ?? 'assets/images');
        $quality = (int) $this->option('quality');
        $force = $this->option('force');

        if (!File::isDirectory($basePath)) {
            $this->error("Directory tidak ditemukan: {$basePath}");
            return Command::FAILURE;
        }

        $extensions = ['jpg', 'jpeg', 'png', 'gif', 'JPG', 'JPEG', 'PNG', 'GIF'];
        
        // Cari semua file gambar secara recursive
        $files = $this->findImages($basePath, $extensions);

        if (empty($files)) {
            $this->info('Tidak ada gambar ditemukan untuk dikonversi.');
            return Command::SUCCESS;
        }

        $this->info('Ditemukan ' . count($files) . ' gambar untuk dikonversi.');
        $this->newLine();

        $converted = 0;
        $skipped = 0;
        $errors = 0;
        $totalSaved = 0;

        $bar = $this->output->createProgressBar(count($files));
        $bar->start();

        foreach ($files as $file) {
            try {
                $webpPath = $this->getWebpPath($file);

                // Skip jika sudah ada dan tidak force
                if (File::exists($webpPath) && !$force) {
                    $skipped++;
                    $bar->advance();
                    continue;
                }

                // Get original size
                $originalSize = File::size($file);

                // Konversi ke WebP menggunakan GD
                $image = $this->createImageFromFile($file);
                imagewebp($image, $webpPath, $quality);
                imagedestroy($image);

                // Get new size
                $newSize = File::size($webpPath);
                $saved = $originalSize - $newSize;
                $totalSaved += max(0, $saved);

                $converted++;
            } catch (\Exception $e) {
                $errors++;
                $this->newLine();
                $this->error("Error converting {$file}: " . $e->getMessage());
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->info("Konversi selesai:");
        $this->line("  - Berhasil dikonversi: <info>{$converted}</info>");
        $this->line("  - Dilewati (sudah ada): <comment>{$skipped}</comment>");
        if ($errors > 0) {
            $this->line("  - Error: <error>{$errors}</error>");
        }
        if ($totalSaved > 0) {
            $savedMB = round($totalSaved / 1024 / 1024, 2);
            $this->line("  - Total penghematan: <info>{$savedMB} MB</info>");
        }

        return Command::SUCCESS;
    }

    /**
     * Cari semua file gambar secara recursive.
     */
    protected function findImages(string $directory, array $extensions): array
    {
        $files = [];
        $pattern = $directory . '/*';

        foreach (glob($pattern) as $path) {
            if (is_dir($path)) {
                $files = array_merge($files, $this->findImages($path, $extensions));
            } else {
                $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                if (in_array($ext, array_map('strtolower', $extensions))) {
                    $files[] = $path;
                }
            }
        }

        return $files;
    }

    /**
     * Get path untuk file WebP.
     */
    protected function getWebpPath(string $originalPath): string
    {
        $info = pathinfo($originalPath);
        return $info['dirname'] . '/' . $info['filename'] . '.webp';
    }

    /**
     * Buat resource gambar dari file.
     */
    protected function createImageFromFile(string $path)
    {
        // Gunakan MIME type untuk deteksi format yang benar
        $mimeType = mime_content_type($path);
        
        // Increase memory limit untuk gambar besar
        ini_set('memory_limit', '512M');

        switch ($mimeType) {
            case 'image/jpeg':
                return imagecreatefromjpeg($path);
            case 'image/png':
                return imagecreatefrompng($path);
            case 'image/gif':
                return imagecreatefromgif($path);
            case 'image/webp':
                return imagecreatefromwebp($path);
            default:
                throw new \InvalidArgumentException("Unsupported image format: {$mimeType}");
        }
    }
}
