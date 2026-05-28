# Berita Page Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Menambahkan halaman "Berita" di sisi admin (CMS Filament) dan public-facing, dengan data dinamis dari database yang di-seed via ComproContentSeeder.

**Architecture:** Mengikuti pattern yang sudah ada di halaman "Pengumuman" — Filament CMS page + form schema + blade view + seeder. Data disimpan di tabel `compro_contents` dengan page='berita'.

**Tech Stack:** Laravel, Filament v3, Blade, Tailwind CSS, ComproContentService

---

## File Structure

| File | Action | Responsibility |
|------|--------|----------------|
| `database/seeders/ComproContentSeeder.php` | Modify | Tambah `seedBeritaPage()` |
| `app/Filament/Pages/ComproForms/BeritaForm.php` | Create | Form schema untuk CMS berita |
| `app/Filament/Pages/CmsComproBerita.php` | Create | Filament page untuk manage berita |
| `resources/views/berita.blade.php` | Create | Public-facing berita view |
| `resources/views/compro-preview/berita.blade.php` | Create | Preview mode view |
| `routes/web.php` | Modify | Tambah route `/berita` |
| `app/Http/Controllers/ComproPreviewController.php` | Modify | Tambah 'berita' ke validPages |
| `app/Services/ComproContentService.php` | Modify | Tambah 'berita.daftar' ke REPEATER_KEYS |

---

### Task 1: Seed Data Berita

**Files:**
- Modify: `database/seeders/ComproContentSeeder.php`

- [ ] **Step 1: Tambah `seedBeritaPage()` method dan panggil di `run()`**

Di method `run()`, tambahkan panggilan `$this->seedBeritaPage();` setelah `$this->seedPengumumanPage();` dan tambahkan `'berita'` ke array `$pages` untuk cache clear.

Tambahkan method baru di akhir class:

```php
/**
 * Seed Berita page content.
 */
private function seedBeritaPage(): void
{
    $page = 'berita';

    // Hero Section
    $this->createContent($page, 'hero', 'judul', 'text', 'Berita', 1);
    $this->createContent($page, 'hero', 'deskripsi', 'text', 'Informasi dan berita terbaru seputar kegiatan Patriot Metric dan perguruan tinggi.', 2);

    // Berita Section
    $this->createContent($page, 'berita', 'daftar', 'repeater', [
        [
            'tanggal' => '2025-01-27',
            'judul' => 'Mendiktisaintek Resmikan Menara Wimaya UPN "Veteran" Jawa Timur',
            'excerpt' => 'Menteri Pendidikan Tinggi, Sains, dan Teknologi Brian Yuliarto menekankan pentingnya pemanfaatan bersama sumber daya pendidikan tinggi saat meresmikan Menara Wimaya.',
            'konten' => 'Menteri Pendidikan Tinggi, Sains, dan Teknologi (Mendiktisaintek) Brian Yuliarto menekankan pentingnya pemanfaatan bersama sumber daya pendidikan tinggi, khususnya laboratorium dan peralatan riset, agar utilisasinya semakin optimal. Hal tersebut disampaikan saat meresmikan Menara Wimaya (Twin Tower) Universitas Pembangunan Nasional (UPN) "Veteran" Jawa Timur, Senin (27/1).

Mendiktisaintek mendorong penguatan kolaborasi antarkampus, baik perguruan tinggi negeri maupun swasta. Menurutnya, praktik berbagi fasilitas dan sumber daya telah menjadi hal yang lazim di berbagai negara dan perlu terus diperkuat di Indonesia. "Gedungnya memang ada di sini, tetapi fasilitas dan laboratoriumnya tentu bisa dimanfaatkan juga oleh kampus-kampus lain. Riset dan pengajaran bisa kita lakukan bersama-sama," ujar Menteri Brian.

Peresmian Menara Wimaya merupakan bagian dari kunjungan kerja Mendiktisaintek ke UPN "Veteran" Jawa Timur sekaligus bentuk apresiasi atas komitmen kampus dalam membangun fasilitas pendidikan tinggi secara mandiri dan berkelanjutan. Ia menilai pembangunan gedung tersebut sebagai capaian penting yang mencerminkan semangat kolegialitas dan kebersamaan antarkampus.

Selain infrastruktur, Mendiktisaintek juga mendorong pengembangan bahan ajar daring berbasis video yang dapat diakses secara luas sebagai implementasi praktik perguruan tinggi kelas dunia. "Dosen-dosen terbaik bisa membagikan ilmunya ke seluas-luasnya masyarakat, dan kampus-kampus di berbagai daerah dapat memanfaatkannya," katanya.

Dalam kesempatan yang sama, Menteri Brian menegaskan bahwa keunggulan perguruan tinggi tidak hanya ditentukan oleh kemegahan infrastruktur, tetapi juga oleh lingkungan kampus yang tertata, bersih, dan memberikan layanan prima. Lingkungan tersebut dinilai penting dalam membentuk karakter, etos kerja, dan integritas mahasiswa sebagai calon sumber daya manusia unggul.

Rektor UPN "Veteran" Jawa Timur, Prof. Dr. Ir. Akhmad Fauzi, MMT.,IPU menyampaikan bahwa pembangunan Menara Wimaya merupakan bagian dari transformasi berkelanjutan kampus bela negara dalam memperkuat atmosfer akademik. "Sejalan dengan berkembangnya UPN \'Veteran\' Jawa Timur, kami merasa perlu membangun fasilitas pendidikan yang mendukung integrasi akademik, riset, dan inovasi agar tercipta lingkungan belajar yang semakin kuat dan berdampak," ujar Rektor.

Menara Wimaya dibangun secara multi-years selama tiga tahun oleh PT PP, dengan peletakan batu pertama pada 16 Juli 2022 dan rampung pada 2025. Gedung kembar seluas sekitar 29.000 meter persegi dan terdiri atas 13 lantai ini didanai melalui Penerimaan Negara Bukan Pajak (PNBP), serta dirancang sebagai simbol konektivitas dan kolaborasi antarbidang keilmuan melalui sky bridge yang menghubungkan kedua menara.

Selain peresmian Menara Wimaya, UPN "Veteran" Jawa Timur juga meluncurkan dua program strategis, yakni Patriot Metric University Ranking dan U-Bridge Program. Kedua program tersebut diposisikan sebagai penguat transformasi institusi, sejalan dengan pembangunan infrastruktur utama kampus dalam mendukung kebijakan Diktisaintek Berdampak dan upaya mencetak lulusan berkarakter, berdaya saing, serta berkontribusi nyata bagi bangsa.',
            'gambar' => '',
        ],
        [
            'tanggal' => '2025-01-27',
            'judul' => 'Manfaat Pemeringkatan Patriot Metric bagi Perguruan Tinggi',
            'excerpt' => 'Patriot Metric memberikan berbagai manfaat strategis bagi perguruan tinggi yang berpartisipasi dalam pemeringkatan.',
            'konten' => 'Meningkatkan Kesadaran Bela Negara; Mendorong Perguruan Tinggi untuk mewujudkan dan meningkatkan karakter bela negara.

Membangun Jejaring dan Kolaborasi Nasional; Membuka peluang bagi perguruan tinggi peserta menjadi bagian dari jejaring Patriot Metric yang memungkinkan kolaborasi di tingkat nasional.

Mendapatkan Pengakuan dan Reputasi; Meningkatkan citra dan reputasi perguruan tinggi sebagai kampus yang berkomitmen pada penguatan karakter bela negara.

Mendorong Perubahan dan Aksi Sosial; Perguruan tinggi dapat melakukan perubahan karakter dalam segala aspek Tridharma Perguruan Tinggi.',
            'gambar' => '',
        ],
    ], 1);
}
```

- [ ] **Step 2: Update `run()` method**

Tambahkan di `run()`:
```php
DB::transaction(function () {
    $this->seedWelcomePage();
    $this->seedProfilePage();
    $this->seedTimPage();
    $this->seedPenghargaanPage();
    $this->seedPanduanPage();
    $this->seedPengumumanPage();
    $this->seedBeritaPage(); // <-- tambah ini
});

$pages = ['welcome', 'profile', 'tim', 'penghargaan', 'panduan', 'pengumuman', 'berita']; // <-- tambah 'berita'
```

- [ ] **Step 3: Commit**

```bash
git add database/seeders/ComproContentSeeder.php
git commit -m "feat: add berita page seed data to ComproContentSeeder"
```

---

### Task 2: Update ComproContentService

**Files:**
- Modify: `app/Services/ComproContentService.php:17-22`

- [ ] **Step 1: Tambah 'berita.daftar' ke REPEATER_KEYS**

Di constant `REPEATER_KEYS` (line 17-22), tambahkan `'berita.daftar'`:

```php
private const REPEATER_KEYS = [
    'institusi.daftar_baris_1', 'institusi.daftar_baris_2',
    'timeline.daftar', 'instagram.posts', 'tujuan-utama.daftar',
    'misi.daftar', 'team-grid.daftar', 'daftar-penerima.daftar',
    'steps.daftar', 'faq.daftar', 'artikel.daftar', 'berita.daftar',
];
```

- [ ] **Step 2: Commit**

```bash
git add app/Services/ComproContentService.php
git commit -m "feat: register berita.daftar as repeater key in ComproContentService"
```

---

### Task 3: Filament CMS Admin Page

**Files:**
- Create: `app/Filament/Pages/ComproForms/BeritaForm.php`
- Create: `app/Filament/Pages/CmsComproBerita.php`

- [ ] **Step 1: Create BeritaForm.php**

```php
<?php

namespace App\Filament\Pages\ComproForms;

use App\Filament\Pages\ComproForms\Concerns\WebpFileUpload;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Filament\Schemas\Components\Section;

class BeritaForm
{
    use WebpFileUpload;

    public static function schema(): array
    {
        return [
            Section::make('Hero')
                ->schema([
                    TextInput::make('hero.judul')->label('Judul')->maxLength(255)->required(),
                    Textarea::make('hero.deskripsi')->label('Deskripsi')->maxLength(500)->required(),
                ]),

            Section::make('Berita')
                ->schema([
                    Repeater::make('berita.daftar')
                        ->label('Daftar Berita')
                        ->schema([
                            DatePicker::make('tanggal')->label('Tanggal')->required(),
                            TextInput::make('judul')->label('Judul Berita')->maxLength(300)->required(),
                            Textarea::make('excerpt')->label('Ringkasan/Excerpt')->maxLength(500)->required(),
                            Textarea::make('konten')->label('Konten Lengkap')->rows(10)->required(),
                            self::makeImageUpload('gambar')
                                ->label('Gambar Thumbnail'),
                        ])
                        ->maxItems(50)
                        ->reorderable()
                        ->collapsible()
                        ->itemLabel(fn(array $state) => ($state['tanggal'] ?? '') . ' - ' . ($state['judul'] ?? 'Berita Baru')),
                ]),
        ];
    }
}
```

- [ ] **Step 2: Create CmsComproBerita.php**

```php
<?php

namespace App\Filament\Pages;

use App\Filament\Pages\ComproForms\BeritaForm;
use Filament\Support\Icons\Heroicon;
use BackedEnum;

class CmsComproBerita extends CmsCompro
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedNewspaper;
    protected static ?string $navigationLabel = 'Berita';
    protected static ?int $navigationSort = 8;
    protected static string $comproPage = 'berita';
    protected static string $formSchemaClass = BeritaForm::class;

    public static function getSlug(?\Filament\Panel $panel = null): string
    {
        return 'cms-compro/berita';
    }
}
```

- [ ] **Step 3: Commit**

```bash
git add app/Filament/Pages/ComproForms/BeritaForm.php app/Filament/Pages/CmsComproBerita.php
git commit -m "feat: add Filament CMS admin page for berita"
```

---

### Task 4: Public-Facing Blade View

**Files:**
- Create: `resources/views/berita.blade.php`
- Create: `resources/views/compro-preview/berita.blade.php`

- [ ] **Step 1: Create `resources/views/berita.blade.php`**

```blade
@inject('comproService', 'App\Services\ComproContentService')

@php
    $content = $comproService->getPageContent('berita');

    $hero = $content->get('hero', collect());
    $berita = $content->get('berita', collect());

    $heroJudul = $hero->firstWhere('key', 'judul')?->value ?? 'Berita';
    $heroDeskripsi = $hero->firstWhere('key', 'deskripsi')?->value ?? 'Informasi dan berita terbaru seputar Patriot Metric.';

    $beritaDaftar = $berita->firstWhere('key', 'daftar')?->value ?? [];
@endphp

<x-layouts.app>
    <div class="bg-white min-h-screen">
        {{-- Hero --}}
        <section class="bg-[#1B5E20]">
            <div class="max-w-[900px] mx-auto px-6 md:px-8 py-20 md:py-28 text-center">
                <h1 class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[34px] md:text-[50px] text-white leading-tight">{{ $heroJudul }}</h1>
                @if($heroDeskripsi)
                    <p class="mt-4 font-['Plus_Jakarta_Sans',sans-serif] text-[16px] md:text-[19px] leading-[28px] text-white/85 max-w-[600px] mx-auto">
                        {{ $heroDeskripsi }}
                    </p>
                @endif
            </div>
        </section>

        {{-- Berita List --}}
        @if(!empty($beritaDaftar))
            <div class="max-w-[1100px] mx-auto px-6 md:px-8 py-10">
                <div class="divide-y divide-[#e2e8f0]">
                    @foreach($beritaDaftar as $index => $item)
                        <div x-data="{ open: false }" class="py-8 first:pt-0 last:pb-0">
                            <div class="flex flex-col md:flex-row gap-5 md:gap-8 cursor-pointer group" @click="open = !open">
                                {{-- Thumbnail --}}
                                <div class="w-full md:w-[240px] h-[160px] md:h-[140px] shrink-0 rounded-lg overflow-hidden bg-[#f1f5f9]">
                                    @if(!empty($item['gambar']))
                                        <img src="{{ url('cms-assets/' . $item['gambar']) }}" alt="{{ $item['judul'] ?? '' }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-[#f1f5f9]">
                                            <span class="text-[#94a3b8] text-[13px]">Gambar</span>
                                        </div>
                                    @endif
                                </div>
                                {{-- Text --}}
                                <div class="flex-1 min-w-0">
                                    @if(!empty($item['tanggal']))
                                        <span class="font-['Plus_Jakarta_Sans',sans-serif] text-[13px] text-[#94a3b8]">
                                            {{ \Carbon\Carbon::parse($item['tanggal'])->translatedFormat('j F Y') }}
                                        </span>
                                    @endif
                                    @if(!empty($item['judul']))
                                        <h2 class="mt-1 font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[18px] md:text-[20px] leading-[28px] text-[#1d293d] group-hover:text-[#1B5E20] transition-colors">{{ $item['judul'] }}</h2>
                                    @endif
                                    @if(!empty($item['excerpt']))
                                        <p class="mt-2 font-['Plus_Jakarta_Sans',sans-serif] text-[15px] leading-[25px] text-[#64748b] line-clamp-2">{{ $item['excerpt'] }}</p>
                                    @endif
                                    <span class="inline-flex items-center gap-1 mt-3 font-['Plus_Jakarta_Sans',sans-serif] font-medium text-[14px] text-[#1B5E20] group-hover:underline">
                                        <span x-text="open ? 'Tutup' : 'Baca selengkapnya'"></span>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 transition-transform duration-200" :class="open ? 'rotate-180' : ''" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                                    </span>
                                </div>
                            </div>

                            {{-- Expandable Content --}}
                            <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="mt-6 ml-0 md:ml-[272px]" style="display: none;">
                                <div class="bg-[#f8fafc] border border-[#e2e8f0] rounded-lg p-6">
                                    <div class="font-['Plus_Jakarta_Sans',sans-serif] text-[15px] leading-[26px] text-[#334155] whitespace-pre-line">{{ $item['konten'] ?? '' }}</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</x-layouts.app>
```

- [ ] **Step 2: Create `resources/views/compro-preview/berita.blade.php`**

```blade
@inject('comproService', 'App\Services\ComproContentService')

@php
    $content = $comproService->getPageContent('berita');

    $hero = $content->get('hero', collect());
    $berita = $content->get('berita', collect());

    $heroJudul = $hero->firstWhere('key', 'judul')?->value ?? 'Berita';
    $heroDeskripsi = $hero->firstWhere('key', 'deskripsi')?->value ?? 'Informasi dan berita terbaru seputar Patriot Metric.';

    $beritaDaftar = $berita->firstWhere('key', 'daftar')?->value ?? [];
@endphp

<div class="bg-white min-h-screen font-['Plus_Jakarta_Sans',sans-serif]">
    {{-- Hero --}}
    <section class="bg-[#1B5E20]">
        <div class="max-w-[900px] mx-auto px-6 md:px-8 py-20 md:py-28 text-center">
            <h1 class="font-bold text-[34px] md:text-[50px] text-white leading-tight">{{ $heroJudul }}</h1>
            @if($heroDeskripsi)
                <p class="mt-4 text-[16px] md:text-[19px] leading-[28px] text-white/85 max-w-[600px] mx-auto">
                    {{ $heroDeskripsi }}
                </p>
            @endif
        </div>
    </section>

    {{-- Berita List --}}
    @if(!empty($beritaDaftar))
        <div class="max-w-[1100px] mx-auto px-6 md:px-8 py-10">
            <div class="divide-y divide-[#e2e8f0]">
                @foreach($beritaDaftar as $item)
                    <div class="py-8 first:pt-0 last:pb-0">
                        <div class="flex flex-col md:flex-row gap-5 md:gap-8">
                            <div class="w-full md:w-[240px] h-[160px] md:h-[140px] shrink-0 rounded-lg overflow-hidden bg-[#f1f5f9]">
                                @if(!empty($item['gambar']))
                                    <img src="{{ url('cms-assets/' . $item['gambar']) }}" alt="{{ $item['judul'] ?? '' }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-[#f1f5f9]">
                                        <span class="text-[#94a3b8] text-[13px]">Gambar</span>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                @if(!empty($item['tanggal']))
                                    <span class="text-[13px] text-[#94a3b8]">{{ \Carbon\Carbon::parse($item['tanggal'])->translatedFormat('j F Y') }}</span>
                                @endif
                                @if(!empty($item['judul']))
                                    <h2 class="mt-1 font-bold text-[18px] md:text-[20px] leading-[28px] text-[#1d293d]">{{ $item['judul'] }}</h2>
                                @endif
                                @if(!empty($item['excerpt']))
                                    <p class="mt-2 text-[15px] leading-[25px] text-[#64748b]">{{ $item['excerpt'] }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
```

- [ ] **Step 3: Commit**

```bash
git add resources/views/berita.blade.php resources/views/compro-preview/berita.blade.php
git commit -m "feat: add berita public and preview blade views"
```

---

### Task 5: Route & Controller Update

**Files:**
- Modify: `routes/web.php`
- Modify: `app/Http/Controllers/ComproPreviewController.php:22`

- [ ] **Step 1: Tambah route di `routes/web.php`**

Tambahkan setelah route `/pengumuman`:

```php
Route::get('/berita', function () {
    return view('berita');
});
```

- [ ] **Step 2: Update ComproPreviewController validPages**

Di `app/Http/Controllers/ComproPreviewController.php` line 22, tambahkan `'berita'`:

```php
$validPages = ['welcome', 'profile', 'tim', 'penghargaan', 'panduan', 'pengumuman', 'berita'];
```

- [ ] **Step 3: Commit**

```bash
git add routes/web.php app/Http/Controllers/ComproPreviewController.php
git commit -m "feat: add berita route and register in preview controller"
```

---

### Task 6: Run Seeder & Verify

- [ ] **Step 1: Run seeder**

```bash
php artisan db:seed --class=ComproContentSeeder
```

- [ ] **Step 2: Verify admin CMS page**

Buka `/admin/cms-compro/berita` — pastikan form muncul dengan data yang di-seed.

- [ ] **Step 3: Verify public page**

Buka `/berita` — pastikan halaman menampilkan daftar berita dengan expand/collapse konten.

- [ ] **Step 4: Verify preview**

Buka `/compro-preview/berita` — pastikan preview mode berfungsi.
