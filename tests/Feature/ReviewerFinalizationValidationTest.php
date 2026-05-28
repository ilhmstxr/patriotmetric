<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReviewerFinalizationValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_finalize_button_uses_validate_method(): void
    {
        $response = $this->get('/reviewer/peserta/1');

        $content = $response->getContent();
        // Button harus memanggil validateBeforeFinalize(), bukan langsung showLockConfirm
        $this->assertStringContainsString('validateBeforeFinalize()', $content);
        $this->assertStringNotContainsString('@click="showLockConfirm = true"', $content);
    }

    public function test_validate_before_finalize_method_exists(): void
    {
        $response = $this->get('/reviewer/peserta/1');

        $content = $response->getContent();
        // Method validateBeforeFinalize harus ada di Alpine x-data
        $this->assertStringContainsString('validateBeforeFinalize()', $content);
        // Harus ada logic scroll ke pertanyaan yang belum diisi
        $this->assertStringContainsString('scrollIntoView', $content);
    }
}
