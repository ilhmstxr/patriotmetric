<?php

namespace App\Http\Controllers;

use App\Services\ComproContentService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ComproPreviewController extends Controller
{
    public function __construct(
        private ComproContentService $contentService
    ) {}

    /**
     * Render a compro page in preview mode (no navbar/footer).
     * Only accessible by authenticated admin users.
     */
    public function show(Request $request, string $page): View
    {
        $validPages = ['welcome', 'profile', 'visi-misi', 'tim', 'penghargaan', 'panduan', 'pengumuman'];

        abort_unless(in_array($page, $validPages), 404);

        $content = $this->contentService->getPageContent($page);

        return view("compro-preview.{$page}", [
            'content' => $content,
            'previewMode' => true,
        ]);
    }
}
