<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReviewerLabelRenameTest extends TestCase
{
    use RefreshDatabase;

    public function test_reviewer_index_uses_perguruan_tinggi_label(): void
    {
        $response = $this->get('/reviewer');

        $content = $response->getContent();
        $this->assertStringContainsString('Nama Perguruan Tinggi', $content);
        $this->assertStringNotContainsString('Nama Institusi', $content);
    }

    public function test_reviewer_panduan_uses_perguruan_tinggi_label(): void
    {
        $response = $this->get('/reviewer/panduan');

        $content = $response->getContent();
        $this->assertStringContainsString('Pilih Perguruan Tinggi dari Daftar Plotting', $content);
        $this->assertStringNotContainsString('Pilih Institusi dari Daftar Plotting', $content);
    }

    public function test_reviewer_detail_uses_perguruan_tinggi_label(): void
    {
        $response = $this->get('/reviewer/peserta/1');

        $content = $response->getContent();
        $this->assertStringContainsString('A. Identitas Perguruan Tinggi', $content);
        $this->assertStringNotContainsString('A. Identitas Institusi', $content);
    }
}
