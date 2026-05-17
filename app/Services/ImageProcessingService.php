<?php

namespace App\Services;

use App\Exceptions\ImageProcessingException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class ImageProcessingService
{
    private const MAX_WIDTH = 1920;
    private const MAX_HEIGHT = 1080;
    private const STORAGE_DISK = 'public';
    private const UPLOAD_PATH = 'compro-images';

    /**
     * Process and store an uploaded image.
     * Converts to WebP, resizes if needed, stores in public disk.
     *
     * @param UploadedFile $file The uploaded image file
     * @param string|null $existingPath Path of existing file to replace (will be deleted)
     * @return string The stored file path relative to disk root
     * @throws ImageProcessingException
     */
    public function processAndStore(UploadedFile $file, ?string $existingPath = null): string
    {
        try {
            // Delete existing file if replacing
            if ($existingPath) {
                $this->delete($existingPath);
            }

            // Process image: resize if exceeds max dimensions
            $image = Image::read($file);

            if ($image->width() > self::MAX_WIDTH || $image->height() > self::MAX_HEIGHT) {
                $image->scaleDown(self::MAX_WIDTH, self::MAX_HEIGHT);
            }

            // Encode as WebP quality 85
            $encoded = $image->toWebp(quality: 85);

            // Generate unique filename
            $filename = uniqid('compro_') . '.webp';
            $path = self::UPLOAD_PATH . '/' . $filename;

            // Store to public disk
            Storage::disk(self::STORAGE_DISK)->put($path, (string) $encoded);

            return $path;
        } catch (ImageProcessingException $e) {
            throw $e;
        } catch (\Throwable $e) {
            Log::error('Image processing failed', [
                'file' => $file->getClientOriginalName(),
                'existingPath' => $existingPath,
                'error' => $e->getMessage(),
            ]);

            throw new ImageProcessingException(
                'Failed to process and store image: ' . $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
    }

    /**
     * Delete an image from storage.
     *
     * @param string $path The file path relative to disk root
     * @return void
     */
    public function delete(string $path): void
    {
        if (Storage::disk(self::STORAGE_DISK)->exists($path)) {
            Storage::disk(self::STORAGE_DISK)->delete($path);
        }
    }
}
