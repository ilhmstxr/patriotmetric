<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReviewerSavingIndicatorTest extends TestCase
{
    use RefreshDatabase;

    public function test_saving_indicator_no_timestamp_display(): void
    {
        $response = $this->get('/reviewer/peserta/1');

        $content = $response->getContent();
        // Harus ada "Tersimpan" tanpa x-text="lastSaved" di sebelahnya
        $this->assertStringNotContainsString('Tersimpan <span x-text="lastSaved">', $content);
        // Tapi tetap ada kata "Tersimpan"
        $this->assertStringContainsString('Tersimpan', $content);
    }

    public function test_saving_indicator_shows_menyimpan(): void
    {
        $response = $this->get('/reviewer/peserta/1');

        $content = $response->getContent();
        $this->assertStringContainsString('Menyimpan...', $content);
    }
}
