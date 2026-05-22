<?php

namespace App\Filament\Pages\ComproForms\Concerns;

use Filament\Forms\Components\FileUpload;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

trait WebpFileUpload
{
    public static function makeImageUpload(string $name): FileUpload
    {
        return FileUpload::make($name)
            ->image()
            ->disk('cms')
            ->directory('images')
            ->visibility('public')
            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
            ->maxSize(5120)
            ->saveUploadedFileUsing(function (TemporaryUploadedFile $file) {
                $image = self::createImageFromFile($file->getRealPath());

                $filename = uniqid('compro_') . '.webp';
                $path = 'images/' . $filename;

                $tempPath = sys_get_temp_dir() . '/' . $filename;
                imagewebp($image, $tempPath, config('image.webp_quality', 80));
                imagedestroy($image);

                Storage::disk('cms')->put($path, file_get_contents($tempPath));
                unlink($tempPath);

                return $path;
            });
    }

    private static function createImageFromFile(string $path)
    {
        $mimeType = mime_content_type($path);

        switch ($mimeType) {
            case 'image/jpeg':
                return imagecreatefromjpeg($path);
            case 'image/png':
                $img = imagecreatefrompng($path);
                imagesavealpha($img, true);
                return $img;
            case 'image/gif':
                return imagecreatefromgif($path);
            case 'image/webp':
                return imagecreatefromwebp($path);
            default:
                throw new \InvalidArgumentException("Unsupported image format: {$mimeType}");
        }
    }
}
