<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReviewerHeaderBadgeTest extends TestCase
{
    use RefreshDatabase;

    public function test_reviewer_badge_not_in_navbar(): void
    {
        $response = $this->get('/reviewer');

        // Badge "Reviewer" tidak boleh ada di navbar (role indicator div sudah dihapus)
        $response->assertDontSee('<div class="flex items-center gap-[8px] mr-2">');
    }

    public function test_reviewer_badge_in_header(): void
    {
        $response = $this->get('/reviewer');

        // Badge "Reviewer" harus ada di header (dekat user info)
        $content = $response->getContent();
        $this->assertStringContainsString("userData.nama_pt === 'Reviewer Patriot Metric'", $content);
    }
}
