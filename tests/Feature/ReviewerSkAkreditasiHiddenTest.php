<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReviewerSkAkreditasiHiddenTest extends TestCase
{
    use RefreshDatabase;

    public function test_sk_akreditasi_not_rendered_in_detail(): void
    {
        $response = $this->get('/reviewer/peserta/1');

        $content = $response->getContent();
        // SK Akreditasi sudah di-comment, tidak boleh ter-render di HTML
        $this->assertStringNotContainsString('>SK Akreditasi</span>', $content);
    }
}
