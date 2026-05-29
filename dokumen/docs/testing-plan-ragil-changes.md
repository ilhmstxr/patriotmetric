# Testing Plan: Perubahan dari ragil.md (tag Ilham)

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Memastikan semua perubahan UI di halaman reviewer berfungsi dengan benar.

**Architecture:** Feature tests (PHPUnit) untuk verifikasi rendering view + manual browser testing untuk interaksi Alpine.js.

**Tech Stack:** PHPUnit, Laravel Feature Tests, Browser manual testing

---

## Task 1: Feature Test — Badge Reviewer di Header

**Files:**
- Create: `tests/Feature/ReviewerHeaderBadgeTest.php`

- [x] **Step 1: Write the failing test**

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
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
```

- [x] **Step 2: Run test to verify it fails**

Run: `php artisan test --filter=ReviewerHeaderBadgeTest`
Expected: FAIL (jika badge masih di navbar)

- [x] **Step 3: Verify tests pass with current code**

Run: `php artisan test --filter=ReviewerHeaderBadgeTest`
Expected: PASS (karena perubahan sudah diimplementasi)

- [x] **Step 4: Commit**

```bash
git add tests/Feature/ReviewerHeaderBadgeTest.php
git commit -m "test: add feature test for reviewer badge placement in header"
```

---

## Task 2: Feature Test — Label "Perguruan Tinggi" di Reviewer Pages

**Files:**
- Create: `tests/Feature/ReviewerLabelRenameTest.php`

- [x] **Step 1: Write the failing test**

```php
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
```

- [x] **Step 2: Run test to verify it fails**

Run: `php artisan test --filter=ReviewerLabelRenameTest`
Expected: FAIL (jika label belum diganti)

- [x] **Step 3: Verify tests pass with current code**

Run: `php artisan test --filter=ReviewerLabelRenameTest`
Expected: PASS

- [x] **Step 4: Commit**

```bash
git add tests/Feature/ReviewerLabelRenameTest.php
git commit -m "test: add feature test for institusi to perguruan tinggi label rename"
```

---

## Task 3: Feature Test — SK Akreditasi Di-comment

**Files:**
- Create: `tests/Feature/ReviewerSkAkreditasiHiddenTest.php`

- [x] **Step 1: Write the failing test**

```php
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
```

- [x] **Step 2: Run test to verify**

Run: `php artisan test --filter=ReviewerSkAkreditasiHiddenTest`
Expected: PASS

- [x] **Step 3: Commit**

```bash
git add tests/Feature/ReviewerSkAkreditasiHiddenTest.php
git commit -m "test: verify SK Akreditasi is hidden from reviewer detail"
```

---

## Task 4: Feature Test — Saving Indicator Tanpa Timestamp

**Files:**
- Create: `tests/Feature/ReviewerSavingIndicatorTest.php`

- [x] **Step 1: Write the failing test**

```php
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
```

- [x] **Step 2: Run test to verify**

Run: `php artisan test --filter=ReviewerSavingIndicatorTest`
Expected: PASS

- [x] **Step 3: Commit**

```bash
git add tests/Feature/ReviewerSavingIndicatorTest.php
git commit -m "test: verify saving indicator has no timestamp"
```

---

## Task 5: Feature Test — validateBeforeFinalize Exists

**Files:**
- Create: `tests/Feature/ReviewerFinalizationValidationTest.php`

- [x] **Step 1: Write the failing test**

```php
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
```

- [x] **Step 2: Run test to verify**

Run: `php artisan test --filter=ReviewerFinalizationValidationTest`
Expected: PASS

- [x] **Step 3: Commit**

```bash
git add tests/Feature/ReviewerFinalizationValidationTest.php
git commit -m "test: verify finalization validation method exists and is wired"
```

---

## Task 6: Manual Browser Testing Checklist

Karena perubahan melibatkan Alpine.js (client-side), beberapa hal hanya bisa diverifikasi via browser:

### Badge Reviewer
- [x] Login sebagai reviewer
- [x] Verifikasi badge "Reviewer" muncul di header (kiri nama akun)
- [x] Verifikasi badge TIDAK ada di navbar baris kedua
- [x] Test di mobile: badge tersembunyi (hidden sm:flex)

### Label Perguruan Tinggi
- [x] Buka `/reviewer` — kolom tabel bertuliskan "Nama Perguruan Tinggi"
- [x] Buka `/reviewer/panduan` — step 1 bertuliskan "Pilih Perguruan Tinggi..."
- [x] Buka `/reviewer/detail?id=X` — section A bertuliskan "A. Identitas Perguruan Tinggi"

### SK Akreditasi
- [x] Buka `/reviewer/detail?id=X` → tab Data Profil → section F. Dokumen Pendukung
- [x] Verifikasi "SK Akreditasi" TIDAK muncul di daftar dokumen

### Saving Indicator
- [x] Buka `/reviewer/detail?id=X` → tab Form Penilaian
- [x] Isi skor pada salah satu pertanyaan, blur (klik di luar)
- [x] Verifikasi indicator berubah: "Menyimpan..." → "Tersimpan" (TANPA jam)
- [x] Per-question indicator juga menampilkan "Menyimpan" → "Tersimpan"

### Validasi Finalisasi
- [x] Buka `/reviewer/detail?id=X` dengan beberapa pertanyaan BELUM dinilai
- [x] Klik "Finalisasi Penilaian"
- [x] Verifikasi: SweetAlert muncul "Masih ada X pertanyaan yang belum dinilai"
- [x] Verifikasi: halaman scroll ke pertanyaan pertama yang kosong
- [x] Verifikasi: pertanyaan tersebut di-highlight (ring merah sementara)
- [x] Isi SEMUA skor
- [x] Klik "Finalisasi Penilaian" lagi
- [x] Verifikasi: modal konfirmasi muncul (bukan error)
- [x] Klik "Ya, Finalisasi" → status berubah ke GRADED

---

## Run All Tests

```bash
php artisan test --filter="Reviewer"
```

Expected: All PASS (Verified 10/10 tests passed)
