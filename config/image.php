<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Image Settings
    |--------------------------------------------------------------------------
    |
    | Konfigurasi untuk pengolahan gambar dan konversi WebP.
    |
    */

    // Kualitas WebP (0-100)
    'webp_quality' => env('IMAGE_WEBP_QUALITY', 80),

    // Dimensi maksimal untuk resize otomatis
    'max_width' => env('IMAGE_MAX_WIDTH', 1920),
    'max_height' => env('IMAGE_MAX_HEIGHT', 1080),

    // Thumbnail dimensions
    'thumbnail_width' => 300,
    'thumbnail_height' => 300,

    // Disk storage untuk gambar
    'disk' => env('IMAGE_DISK', 'public'),

    // Directory untuk menyimpan gambar
    'upload_directory' => 'uploads/images',
    'thumbnail_directory' => 'uploads/thumbnails',

    // Extensions yang akan dikonversi ke WebP
    'convertible_extensions' => ['jpg', 'jpeg', 'png', 'gif'],

    // Auto-convert saat upload
    'auto_convert' => env('IMAGE_AUTO_CONVERT', true),
];
