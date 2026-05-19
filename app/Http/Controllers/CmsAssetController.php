<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

class CmsAssetController extends Controller
{
    public function show(string $path)
    {
        $disk = Storage::disk('cms');

        if (!$disk->exists($path)) {
            abort(404);
        }

        $mimeType = $disk->mimeType($path);
        $lastModified = $disk->lastModified($path);

        return response()->file($disk->path($path), [
            'Content-Type' => $mimeType,
            'Cache-Control' => 'public, max-age=86400',
            'Last-Modified' => gmdate('D, d M Y H:i:s', $lastModified) . ' GMT',
        ]);
    }
}
