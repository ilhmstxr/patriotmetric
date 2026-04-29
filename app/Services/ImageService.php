<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class ImageService
{
    protected int $quality;

    public function __construct()
    {
        $this->quality = config('image.webp_quality', 80);
    }

    /**
     * Konversi uploaded image ke WebP dan simpan.
     *
     * @param UploadedFile $file
     * @param string $directory Directory tujuan di dalam disk storage
     * @param string $disk Disk storage (default: public)
     * @return array ['path' => 'path/to/file.webp', 'original_name' => 'name', 'size' => bytes]
     */
    public function convertAndSave(UploadedFile $file, string $directory = 'images', string $disk = 'public'): array
    {
        // Generate nama file unik
        $filename = uniqid() . '.webp';
        $path = $directory . '/' . $filename;

        // Buat gambar dari file upload
        $image = $this->createImageFromFile($file->getRealPath());

        // Konversi ke WebP
        $tempPath = sys_get_temp_dir() . '/' . $filename;
        imagewebp($image, $tempPath, $this->quality);
        imagedestroy($image);

        // Simpan ke storage
        Storage::disk($disk)->put($path, file_get_contents($tempPath));
        unlink($tempPath);

        return [
            'path' => $path,
            'url' => Storage::disk($disk)->url($path),
            'original_name' => $file->getClientOriginalName(),
            'size' => Storage::disk($disk)->size($path),
        ];
    }

    /**
     * Konversi file yang sudah ada ke WebP.
     *
     * @param string $sourcePath Path file sumber (absolute)
     * @param string $targetPath Path file tujuan (relative to disk)
     * @param string $disk Disk storage
     * @return string Path file WebP
     */
    public function convertExisting(string $sourcePath, string $targetPath, string $disk = 'public'): string
    {
        $image = $this->createImageFromFile($sourcePath);

        // Pastikan extension .webp
        $info = pathinfo($targetPath);
        $targetPath = $info['dirname'] . '/' . $info['filename'] . '.webp';

        $tempPath = sys_get_temp_dir() . '/' . $info['filename'] . '.webp';
        imagewebp($image, $tempPath, $this->quality);
        imagedestroy($image);

        Storage::disk($disk)->put($targetPath, file_get_contents($tempPath));
        unlink($tempPath);

        return $targetPath;
    }

    /**
     * Resize dan konversi ke WebP.
     *
     * @param UploadedFile $file
     * @param int $maxWidth
     * @param int $maxHeight
     * @param string $directory
     * @param string $disk
     * @return array
     */
    public function resizeAndConvert(
        UploadedFile $file,
        int $maxWidth = 1920,
        int $maxHeight = 1080,
        string $directory = 'images',
        string $disk = 'public'
    ): array {
        $filename = uniqid() . '.webp';
        $path = $directory . '/' . $filename;

        $image = $this->createImageFromFile($file->getRealPath());

        // Resize jika melebihi dimensi maksimal
        $width = imagesx($image);
        $height = imagesy($image);

        if ($width > $maxWidth || $height > $maxHeight) {
            $ratio = min($maxWidth / $width, $maxHeight / $height);
            $newWidth = (int)($width * $ratio);
            $newHeight = (int)($height * $ratio);

            $resized = imagecreatetruecolor($newWidth, $newHeight);
            imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            imagedestroy($image);
            $image = $resized;
            $width = $newWidth;
            $height = $newHeight;
        }

        $tempPath = sys_get_temp_dir() . '/' . $filename;
        imagewebp($image, $tempPath, $this->quality);
        imagedestroy($image);

        Storage::disk($disk)->put($path, file_get_contents($tempPath));
        unlink($tempPath);

        return [
            'path' => $path,
            'url' => Storage::disk($disk)->url($path),
            'original_name' => $file->getClientOriginalName(),
            'size' => Storage::disk($disk)->size($path),
            'width' => $width,
            'height' => $height,
        ];
    }

    /**
     * Buat thumbnail WebP.
     *
     * @param UploadedFile $file
     * @param int $width
     * @param int $height
     * @param string $directory
     * @param string $disk
     * @return array
     */
    public function createThumbnail(
        UploadedFile $file,
        int $width = 300,
        int $height = 300,
        string $directory = 'thumbnails',
        string $disk = 'public'
    ): array {
        $filename = uniqid() . '.webp';
        $path = $directory . '/' . $filename;

        $source = $this->createImageFromFile($file->getRealPath());
        $srcWidth = imagesx($source);
        $srcHeight = imagesy($source);

        // Create thumbnail (crop center)
        $thumb = imagecreatetruecolor($width, $height);
        $srcRatio = $srcWidth / $srcHeight;
        $thumbRatio = $width / $height;

        if ($srcRatio > $thumbRatio) {
            $srcHeight = $srcHeight;
            $srcWidth = (int)($srcHeight * $thumbRatio);
            $srcX = (int)(($srcWidth - $srcWidth) / 2);
            $srcY = 0;
        } else {
            $srcWidth = $srcWidth;
            $srcHeight = (int)($srcWidth / $thumbRatio);
            $srcX = 0;
            $srcY = (int)(($srcHeight - $srcHeight) / 2);
        }

        imagecopyresampled($thumb, $source, 0, 0, $srcX, $srcY, $width, $height, $srcWidth, $srcHeight);
        imagedestroy($source);

        $tempPath = sys_get_temp_dir() . '/' . $filename;
        imagewebp($thumb, $tempPath, $this->quality);
        imagedestroy($thumb);

        Storage::disk($disk)->put($path, file_get_contents($tempPath));
        unlink($tempPath);

        return [
            'path' => $path,
            'url' => Storage::disk($disk)->url($path),
            'size' => Storage::disk($disk)->size($path),
        ];
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
