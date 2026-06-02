<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Services\ComproContentService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ComproPreviewController extends Controller
{
    public function __construct(
        private ComproContentService $contentService
    ) {}

    /**
     * Render the public welcome page with the 3 latest news items.
     */
    public function welcome(): View
    {
        $latestNews = Berita::published()
            ->latest('tanggal')
            ->take(3)
            ->get();

        return view('welcome', compact('latestNews'));
    }

    /**
     * Render a compro page in preview mode (no navbar/footer).
     * Only accessible by authenticated admin users.
     */
    public function show(Request $request, string $page): View
    {
        $validPages = ['welcome', 'profile', 'tim', 'penghargaan', 'panduan', 'pengumuman', 'berita'];

        abort_unless(in_array($page, $validPages), 404);

        $content = $this->contentService->getPageContent($page);

        return view("compro-preview.{$page}", [
            'content' => $content,
            'previewMode' => true,
        ]);
    }
}
