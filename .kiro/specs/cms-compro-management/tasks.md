# Implementation Plan: CMS Compro Management

## Overview

Implementasi CMS Compro Management untuk mengelola konten 7 halaman company profile Patriot Metric melalui panel admin Filament v3. Sistem menggunakan tabel dedicated `compro_contents`, service layer dengan caching, Filament Custom Page dengan 7 tabs, preview system via iframe, dan seeder untuk migrasi data hardcoded.

## Tasks

- [ ] 1. Setup database, model, dan DTO
  - [ ] 1.1 Create migration for `compro_contents` table
    - Create migration file `database/migrations/xxxx_create_compro_contents_table.php`
    - Define columns: id, page (varchar 50, indexed), section (varchar 100, indexed), key (varchar 150), type (enum: text/richtext/image/url/repeater), value (longText nullable), order (unsigned integer default 0), timestamps
    - Add composite unique constraint on (page, section, key)
    - _Requirements: 7.2_

  - [ ] 1.2 Create ComproContent model
    - Create `app/Models/ComproContent.php`
    - Define fillable fields: page, section, key, type, value, order
    - Implement value Attribute accessor/mutator (JSON decode for repeater type, JSON encode for arrays)
    - Add scopes: `forPage($page)`, `forSection($section)`, `static()`, `repeater()`
    - Cast `order` to integer
    - _Requirements: 1.2, 2.1_

  - [ ] 1.3 Create ComproContentDTO
    - Create `app/DTO/ComproContentDTO.php`
    - Define readonly class with properties: page, section, key, type, value (string|array|null), order
    - Implement `fromModel(ComproContent)` and `fromArray(array)` static factory methods
    - _Requirements: 1.2_

- [ ] 2. Implement service layer
  - [ ] 2.1 Install intervention/image package
    - Run `composer require intervention/image`
    - Publish config if needed for Laravel integration
    - _Requirements: 5.1_

  - [ ] 2.2 Implement ComproContentService
    - Create `app/Services/ComproContentService.php`
    - Implement `getPageContent(string $page): Collection` — cached (1 hour TTL), grouped by section, ordered by section then order
    - Implement `getValue(string $page, string $section, string $key): string|array|null`
    - Implement `updateStaticContent(string $page, array $data): void` — uses updateOrCreate, wraps in DB transaction, clears cache
    - Implement `updateRepeaterContent(string $page, string $section, string $key, array $items): void` — updateOrCreate with type=repeater, clears cache
    - Implement `clearCache(string $page): void`
    - Implement `getPageStructure(): array` — returns 7 pages with their sections
    - Add error handling with Log::error and re-throw pattern
    - _Requirements: 1.3, 2.3, 4.1, 7.5_

  - [ ]* 2.3 Write property test: Static Content Round-Trip (Property 1)
    - **Property 1: Static Content Round-Trip**
    - **Validates: Requirements 1.3**
    - Create `tests/Feature/ComproContentRoundTripTest.php`
    - Use Faker to generate random valid static content values (text, richtext, url)
    - Save via `updateStaticContent()`, retrieve via `getValue()`, assert exact equality
    - Minimum 100 iterations via data provider

  - [ ] 2.4 Implement ImageProcessingService
    - Create `app/Services/ImageProcessingService.php`
    - Implement `processAndStore(UploadedFile $file, ?string $existingPath = null): string`
    - Delete existing file if replacing (call `delete()`)
    - Read image with Intervention Image, scaleDown if exceeds 1920x1080
    - Encode as WebP quality 85, store in `public` disk under `compro-images/` directory
    - Generate unique filename with `uniqid('compro_') . '.webp'`
    - Implement `delete(string $path): void` — check existence then delete from storage
    - Add try/catch with Log::error, throw ImageProcessingException on failure
    - _Requirements: 5.1, 5.2, 5.3, 5.4_

  - [ ]* 2.5 Write property test: Image Processing Pipeline (Property 5)
    - **Property 5: Image Processing Pipeline**
    - **Validates: Requirements 5.1, 5.2, 5.3**
    - Create `tests/Feature/ComproImageProcessingTest.php`
    - Generate random valid image files (JPEG, PNG, WebP) with various dimensions
    - Assert output is WebP, dimensions ≤ 1920x1080, aspect ratio preserved, file exists on disk
    - Minimum 100 iterations

- [ ] 3. Checkpoint - Ensure migration runs and services work
  - Ensure all tests pass, ask the user if questions arise.

- [ ] 4. Build Filament Custom Page with 7 form schemas
  - [ ] 4.1 Create CmsCompro Filament Page
    - Create `app/Filament/Pages/CmsCompro.php`
    - Set view to `filament.pages.cms-compro`
    - Configure navigation: icon `heroicon-o-globe-alt`, label "CMS Compro", group "Konten Website", sort 1
    - Define properties: `$data = []`, `$activeTab = 'welcome'`, `$showPreview = true`
    - Implement `mount()` — load content for active tab from ComproContentService
    - Implement `updatedActiveTab()` — reload form data when tab changes
    - Implement `save()` — persist form data (static + repeater), clear cache, dispatch 'content-saved' event for preview refresh
    - Implement `form(Form $form)` — dynamically load schema from form class based on activeTab
    - Handle image uploads via ImageProcessingService within save method
    - _Requirements: 1.1, 1.2, 1.3, 3.1, 3.2, 4.1, 4.6_

  - [ ] 4.2 Create Filament page Blade view
    - Create `resources/views/filament/pages/cms-compro.blade.php`
    - Layout: flex row with form panel (left) and preview panel (right) using Alpine.js x-data
    - Tab navigation: 7 buttons (welcome, profile, visi-misi, tim, penghargaan, panduan, pengumuman) with wire:click to switch activeTab
    - Form content: wire:submit="save" with `{{ $this->form }}` and submit button
    - Preview panel: sticky iframe with src pointing to compro.preview route, toggle show/hide
    - JavaScript: listen for 'content-saved' event to reload iframe
    - Responsive: full width form when preview hidden, 50/50 split when shown
    - _Requirements: 3.1, 3.4, 3.5, 3.6, 4.1_

  - [ ] 4.3 Create WelcomeForm schema
    - Create `app/Filament/Pages/ComproForms/WelcomeForm.php`
    - Sections: Hero (judul, deskripsi, background_image), About (judul, deskripsi as RichEditor with toolbar buttons, video_url), Institusi Partisipan (judul, deskripsi, daftar_baris_1 repeater with nama+logo, daftar_baris_2 repeater with nama+logo), Timeline (judul, deskripsi, daftar repeater with nomor+tanggal+judul+deskripsi), Instagram (judul, deskripsi, posts repeater with url+gambar+alt_text)
    - No CTA button fields in Hero section
    - About deskripsi = 1 RichEditor field (merged paragraphs + bullets)
    - Institutions use nama + logo (not singkatan)
    - Section titles for Institusi Partisipan and Timeline
    - _Requirements: 1.2, 1.4, 2.5_

  - [ ] 4.4 Create ProfileForm schema
    - Create `app/Filament/Pages/ComproForms/ProfileForm.php`
    - Sections: Hero (judul, deskripsi, background_image), Latar Belakang (judul, deskripsi as RichEditor), Tujuan Utama (judul, deskripsi, daftar repeater with nomor+judul+deskripsi)
    - Latar Belakang deskripsi = 1 RichEditor field (merged paragraphs)
    - _Requirements: 1.2, 1.4, 2.5_

  - [ ] 4.5 Create VisiMisiForm schema
    - Create `app/Filament/Pages/ComproForms/VisiMisiForm.php`
    - Sections: Hero (judul, deskripsi), Visi (teks textarea), Misi (judul, daftar repeater with nomor+judul+deskripsi)
    - _Requirements: 1.2, 1.4, 2.5_

  - [ ] 4.6 Create TimForm schema
    - Create `app/Filament/Pages/ComproForms/TimForm.php`
    - Sections: Hero (judul, deskripsi), Team Grid (daftar repeater with nama+role+foto)
    - _Requirements: 1.2, 1.4, 2.5_

  - [ ] 4.7 Create PenghargaanForm schema
    - Create `app/Filament/Pages/ComproForms/PenghargaanForm.php`
    - Sections: Hero (judul, deskripsi, background_image), Daftar Penerima (judul, daftar repeater with nama+logo+rating)
    - Award winners include logo field
    - _Requirements: 1.2, 1.4, 2.5_

  - [ ] 4.8 Create PanduanForm schema
    - Create `app/Filament/Pages/ComproForms/PanduanForm.php`
    - Sections: Hero (judul, deskripsi, tombol_teks, tombol_link), Steps (daftar repeater with label+judul+deskripsi+icon), FAQ (judul, daftar repeater with pertanyaan+jawaban)
    - _Requirements: 1.2, 1.4, 2.5_

  - [ ] 4.9 Create PengumumanForm schema
    - Create `app/Filament/Pages/ComproForms/PengumumanForm.php`
    - Sections: Hero (judul, deskripsi), Artikel (daftar repeater with tanggal DatePicker+judul+excerpt+gambar)
    - _Requirements: 1.2, 1.4, 2.5_

  - [ ]* 4.10 Write property test: Content Validation Correctness (Property 2)
    - **Property 2: Content Validation Correctness**
    - **Validates: Requirements 1.4, 1.5, 2.5, 2.6**
    - Create `tests/Feature/ComproContentValidationTest.php`
    - Generate random valid and invalid inputs for each field type
    - Assert valid inputs pass validation, invalid inputs are rejected with specific field error messages
    - Minimum 100 iterations

- [ ] 5. Checkpoint - Ensure Filament page renders and forms work
  - Ensure all tests pass, ask the user if questions arise.

- [ ] 6. Build preview system
  - [ ] 6.1 Create ComproPreviewController
    - Create `app/Http/Controllers/ComproPreviewController.php`
    - Inject ComproContentService via constructor
    - Implement `show(Request $request, string $page): View`
    - Validate page against 7 valid slugs, abort 404 if invalid
    - Load page content via service, pass to view with `previewMode = true`
    - _Requirements: 3.1, 3.3, 3.5_

  - [ ] 6.2 Register preview route
    - Add route in `routes/web.php`: `GET /admin/compro-preview/{page}`
    - Apply middleware: `auth`, `verified` (admin-only access)
    - Name route: `compro.preview`
    - _Requirements: 3.5_

  - [ ] 6.3 Create preview Blade template for Welcome page
    - Create `resources/views/compro-preview/welcome.blade.php`
    - Render all sections (hero, about, institusi, timeline, instagram) using `$content` data
    - No navbar/footer, include Tailwind CSS for styling
    - Match public page layout and styling exactly
    - _Requirements: 3.3_

  - [ ] 6.4 Create preview Blade template for Profile page
    - Create `resources/views/compro-preview/profile.blade.php`
    - Render hero, latar-belakang (rich text), tujuan-utama sections
    - _Requirements: 3.3_

  - [ ] 6.5 Create preview Blade template for Visi-Misi page
    - Create `resources/views/compro-preview/visi-misi.blade.php`
    - Render hero, visi, misi sections
    - _Requirements: 3.3_

  - [ ] 6.6 Create preview Blade template for Tim page
    - Create `resources/views/compro-preview/tim.blade.php`
    - Render hero, team-grid sections
    - _Requirements: 3.3_

  - [ ] 6.7 Create preview Blade template for Penghargaan page
    - Create `resources/views/compro-preview/penghargaan.blade.php`
    - Render hero, daftar-penerima sections (with logo + rating stars)
    - _Requirements: 3.3_

  - [ ] 6.8 Create preview Blade template for Panduan page
    - Create `resources/views/compro-preview/panduan.blade.php`
    - Render hero (with pedoman button), steps, faq sections
    - _Requirements: 3.3_

  - [ ] 6.9 Create preview Blade template for Pengumuman page
    - Create `resources/views/compro-preview/pengumuman.blade.php`
    - Render hero, artikel sections (with date, title, excerpt, thumbnail)
    - _Requirements: 3.3_

- [ ] 7. Create seeder for all 7 pages
  - [ ] 7.1 Implement ComproContentSeeder
    - Create `database/seeders/ComproContentSeeder.php`
    - Wrap all operations in DB::transaction for atomicity
    - Use `firstOrCreate` for idempotency (skip existing records)
    - Implement private methods: `seedWelcomePage()`, `seedProfilePage()`, `seedVisiMisiPage()`, `seedTimPage()`, `seedPenghargaanPage()`, `seedPanduanPage()`, `seedPengumumanPage()`
    - Extract content from existing blade templates (welcome.blade.php, profile.blade.php, etc.)
    - Seed all static content (hero titles, descriptions, background images) and repeater content (institutions with nama+logo, timeline items, team members, FAQ, steps, award winners with nama+logo+rating, articles with tanggal+judul+excerpt+gambar)
    - Helper method: `createContent(page, section, key, type, value, order)`
    - _Requirements: 6.1, 6.2, 6.3, 6.4, 6.5_

  - [ ]* 7.2 Write property test: Seeder Idempotency (Property 6)
    - **Property 6: Seeder Idempotency**
    - **Validates: Requirements 6.4**
    - Create `tests/Feature/ComproSeederIdempotencyTest.php`
    - Run seeder, modify some records, run seeder again
    - Assert modified records are NOT overwritten, only new keys are created
    - Minimum 100 iterations

- [ ] 8. Checkpoint - Ensure seeder runs and preview system works
  - Ensure all tests pass, ask the user if questions arise.

- [ ] 9. Update public Blade views to read from ComproContentService
  - [ ] 9.1 Update welcome.blade.php
    - Inject ComproContentService data via controller or view composer
    - Replace hardcoded hero content (judul, deskripsi, background) with service values
    - Replace hardcoded about section (judul, rich text deskripsi, video_url) with service values
    - Replace hardcoded institusi section (judul, deskripsi, daftar_baris_1, daftar_baris_2) with service values — display nama + logo
    - Replace hardcoded timeline section (judul, deskripsi, daftar) with service values
    - Replace hardcoded instagram section (judul, deskripsi, posts) with service values
    - Hide sections with zero repeater items (Requirement 2.7)
    - _Requirements: 1.3, 2.7, 6.3_

  - [ ] 9.2 Update profile.blade.php
    - Replace hardcoded hero, latar-belakang (render rich text HTML), tujuan-utama with service values
    - Hide tujuan-utama section if repeater is empty
    - _Requirements: 1.3, 2.7, 6.3_

  - [ ] 9.3 Update visi-misi Blade view
    - Replace hardcoded hero, visi, misi content with service values
    - Hide misi section if repeater is empty
    - _Requirements: 1.3, 2.7, 6.3_

  - [ ] 9.4 Update tim.blade.php
    - Replace hardcoded hero, team-grid content with service values
    - Hide team-grid section if repeater is empty
    - _Requirements: 1.3, 2.7, 6.3_

  - [ ] 9.5 Update penghargaan.blade.php
    - Replace hardcoded hero, daftar-penerima content with service values
    - Display award winners with nama, logo, and rating stars
    - Hide daftar-penerima section if repeater is empty
    - _Requirements: 1.3, 2.7, 6.3_

  - [ ] 9.6 Update panduan.blade.php
    - Replace hardcoded hero (with pedoman button), steps, faq content with service values
    - Hide steps/faq sections if repeater is empty
    - _Requirements: 1.3, 2.7, 6.3_

  - [ ] 9.7 Update or create pengumuman.blade.php
    - Create public view for pengumuman page if not exists, or update existing
    - Render hero, artikel list (tanggal, judul, excerpt, gambar thumbnail) from service values
    - Hide artikel section if repeater is empty
    - _Requirements: 1.3, 2.7, 6.3_

  - [ ]* 9.8 Write property test: Data Isolation Invariant (Property 7)
    - **Property 7: Data Isolation Invariant**
    - **Validates: Requirements 7.5**
    - Create `tests/Feature/ComproDataIsolationTest.php`
    - Perform random CRUD operations on compro_contents
    - Assert pengaturan_cms table remains unchanged (same row count, same values)
    - Minimum 100 iterations

- [ ] 10. Checkpoint - Ensure public pages render correctly from CMS data
  - Ensure all tests pass, ask the user if questions arise.

- [ ] 11. Integration testing and remaining property tests
  - [ ]* 11.1 Write property test: Repeater Ordering Invariant (Property 3)
    - **Property 3: Repeater Ordering Invariant**
    - **Validates: Requirements 2.2, 2.3**
    - Create `tests/Feature/ComproRepeaterOrderingTest.php`
    - Add items to repeater, assert count increases by 1
    - Reorder items with random permutations, assert retrieval matches new order
    - Minimum 100 iterations

  - [ ]* 11.2 Write property test: Search Filter Correctness (Property 4)
    - **Property 4: Search Filter Correctness**
    - **Validates: Requirements 4.3**
    - Create `tests/Feature/ComproSearchFilterTest.php`
    - Generate random content blocks and search terms (≥2 chars)
    - Assert results contain exactly matching items (no false positives/negatives)
    - Minimum 100 iterations

  - [ ]* 11.3 Write integration tests for preview route
    - Create `tests/Feature/ComproPreviewRouteTest.php`
    - Test auth middleware (unauthenticated → redirect)
    - Test valid page slugs (7 pages) return 200
    - Test invalid page slug returns 404
    - Test rendered output contains expected content
    - _Requirements: 3.5, 3.6_

  - [ ]* 11.4 Write integration tests for Filament CmsCompro page
    - Create `tests/Feature/CmsComproPageTest.php`
    - Test page loads for authenticated admin
    - Test tab switching loads correct form schema
    - Test form submission saves content correctly
    - Test preview iframe URL updates on tab switch
    - _Requirements: 1.1, 1.2, 1.3, 3.2, 4.6_

- [ ] 12. Final checkpoint - Ensure all tests pass
  - Ensure all tests pass, ask the user if questions arise.

## Notes

- Tasks marked with `*` are optional and can be skipped for faster MVP
- Each task references specific requirements for traceability
- Checkpoints ensure incremental validation
- Property tests validate universal correctness properties using Faker with 100+ iterations
- Unit tests validate specific examples and edge cases
- The project uses Laravel 11, Filament v3, MySQL, Blade templates, Tailwind CSS
- intervention/image is required for WebP conversion and image resizing
- All 7 pages: Welcome, Profile, Visi-Misi, Tim, Penghargaan, Panduan, Pengumuman
- Key design changes reflected: no CTA buttons in Welcome hero, no Instagram account link, institutions use nama+logo, award winners include logo, About/Latar Belakang use single rich text fields, section titles for Institusi Partisipan and Timeline, PengumumanForm added

## Task Dependency Graph

```json
{
  "waves": [
    { "id": 0, "tasks": ["1.1"] },
    { "id": 1, "tasks": ["1.2", "1.3", "2.1"] },
    { "id": 2, "tasks": ["2.2", "2.4"] },
    { "id": 3, "tasks": ["2.3", "2.5", "4.1", "4.2"] },
    { "id": 4, "tasks": ["4.3", "4.4", "4.5", "4.6", "4.7", "4.8", "4.9"] },
    { "id": 5, "tasks": ["4.10", "6.1", "6.2", "7.1"] },
    { "id": 6, "tasks": ["6.3", "6.4", "6.5", "6.6", "6.7", "6.8", "6.9", "7.2"] },
    { "id": 7, "tasks": ["9.1", "9.2", "9.3", "9.4", "9.5", "9.6", "9.7"] },
    { "id": 8, "tasks": ["9.8", "11.1", "11.2", "11.3", "11.4"] }
  ]
}
```
