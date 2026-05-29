# Berita Detail Page Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Create a dedicated `beritas` database table, model, and Filament resource so that berita articles are stored as proper records. When a user clicks an article on `/berita`, they navigate to `/berita/{slug}` showing the full article detail with image.

**Architecture:** Replace the CMS-based berita (compro_contents repeater) with a dedicated Eloquent model backed by its own table. Create a Filament Resource for CRUD in admin. Add a BeritaController with `index` (listing) and `show` (detail) actions. The listing page links each article to its detail page.

**Tech Stack:** Laravel 12, Filament v4, Blade, Eloquent, CMS disk for images

---

## File Structure

| Action | File | Responsibility |
|--------|------|----------------|
| Create | `database/migrations/2026_05_29_000001_create_beritas_table.php` | Migration for beritas table |
| Create | `app/Models/Berita.php` | Eloquent model |
| Create | `app/Http/Controllers/BeritaController.php` | Public index + show actions |
| Create | `app/Filament/Resources/BeritaResource.php` | Filament admin CRUD |
| Create | `app/Filament/Resources/BeritaResource/Pages/ListBeritas.php` | Filament list page |
| Create | `app/Filament/Resources/BeritaResource/Pages/CreateBerita.php` | Filament create page |
| Create | `app/Filament/Resources/BeritaResource/Pages/EditBerita.php` | Filament edit page |
| Modify | `routes/web.php:44-46` | Replace closure with controller routes |
| Modify | `resources/views/berita.blade.php` | Pull from Berita model instead of CMS |
| Create | `resources/views/berita-detail.blade.php` | Detail page view |

---

### Task 1: Create Migration

**Files:**
- Create: `database/migrations/2026_05_29_000001_create_beritas_table.php`

- [ ] **Step 1: Create the migration file**

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('beritas', function (Blueprint $table) {
            $table->id();
            $table->string('judul', 300);
            $table->string('slug', 350)->unique();
            $table->text('excerpt');
            $table->longText('konten');
            $table->string('gambar')->nullable();
            $table->date('tanggal');
            $table->boolean('is_published')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('beritas');
    }
};
```

- [ ] **Step 2: Run the migration**

Run: `php artisan migrate`
Expected: "Running migrations... create_beritas_table ... DONE"

- [ ] **Step 3: Commit**

```bash
git add database/migrations/2026_05_29_000001_create_beritas_table.php
git commit -m "feat: add beritas table migration"
```

---

### Task 2: Create Berita Model

**Files:**
- Create: `app/Models/Berita.php`

- [ ] **Step 1: Create the model**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Berita extends Model
{
    protected $table = 'beritas';

    protected $fillable = [
        'judul',
        'slug',
        'excerpt',
        'konten',
        'gambar',
        'tanggal',
        'is_published',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'is_published' => 'boolean',
    ];

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public static function booted(): void
    {
        static::creating(function (Berita $berita) {
            if (empty($berita->slug)) {
                $berita->slug = Str::slug($berita->judul) . '-' . Str::random(5);
            }
        });
    }
}
```

- [ ] **Step 2: Commit**

```bash
git add app/Models/Berita.php
git commit -m "feat: add Berita model"
```

---

### Task 3: Create Filament Resource

**Files:**
- Create: `app/Filament/Resources/BeritaResource.php`
- Create: `app/Filament/Resources/BeritaResource/Pages/ListBeritas.php`
- Create: `app/Filament/Resources/BeritaResource/Pages/CreateBerita.php`
- Create: `app/Filament/Resources/BeritaResource/Pages/EditBerita.php`

- [ ] **Step 1: Create the resource directory**

Run: `mkdir -p app/Filament/Resources/BeritaResource/Pages` (PowerShell: `New-Item -ItemType Directory -Force app/Filament/Resources/BeritaResource/Pages`)

- [ ] **Step 2: Create BeritaResource.php**

```php
<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BeritaResource\Pages;
use App\Models\Berita;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BeritaResource extends Resource
{
    protected static ?string $model = Berita::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    protected static ?string $navigationGroup = 'CMS Compro';

    protected static ?string $navigationLabel = 'Berita';

    protected static ?int $navigationSort = 9;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('judul')
                ->label('Judul')
                ->required()
                ->maxLength(300),
            TextInput::make('slug')
                ->label('Slug')
                ->maxLength(350)
                ->unique(ignoreRecord: true)
                ->helperText('Otomatis dibuat dari judul jika dikosongkan'),
            Textarea::make('excerpt')
                ->label('Ringkasan')
                ->required()
                ->maxLength(500),
            RichEditor::make('konten')
                ->label('Konten Lengkap')
                ->required()
                ->columnSpanFull(),
            FileUpload::make('gambar')
                ->label('Gambar')
                ->image()
                ->disk('cms')
                ->directory('images')
                ->maxSize(2048),
            DatePicker::make('tanggal')
                ->label('Tanggal')
                ->required()
                ->default(now()),
            Toggle::make('is_published')
                ->label('Publikasikan')
                ->default(false),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('gambar')->disk('cms')->label('Gambar')->width(80)->height(50),
                TextColumn::make('judul')->label('Judul')->searchable()->limit(50),
                TextColumn::make('tanggal')->label('Tanggal')->date('j M Y')->sortable(),
                IconColumn::make('is_published')->label('Published')->boolean(),
                TextColumn::make('updated_at')->label('Diperbarui')->since()->sortable(),
            ])
            ->defaultSort('tanggal', 'desc')
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBeritas::route('/'),
            'create' => Pages\CreateBerita::route('/create'),
            'edit' => Pages\EditBerita::route('/{record}/edit'),
        ];
    }
}
```

- [ ] **Step 3: Create ListBeritas.php**

```php
<?php

namespace App\Filament\Resources\BeritaResource\Pages;

use App\Filament\Resources\BeritaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBeritas extends ListRecords
{
    protected static string $resource = BeritaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
```

- [ ] **Step 4: Create CreateBerita.php**

```php
<?php

namespace App\Filament\Resources\BeritaResource\Pages;

use App\Filament\Resources\BeritaResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBerita extends CreateRecord
{
    protected static string $resource = BeritaResource::class;
}
```

- [ ] **Step 5: Create EditBerita.php**

```php
<?php

namespace App\Filament\Resources\BeritaResource\Pages;

use App\Filament\Resources\BeritaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditBerita extends EditRecord
{
    protected static string $resource = BeritaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
```

- [ ] **Step 6: Commit**

```bash
git add app/Filament/Resources/BeritaResource.php app/Filament/Resources/BeritaResource/
git commit -m "feat: add Filament BeritaResource for admin CRUD"
```

---

### Task 4: Create BeritaController

**Files:**
- Create: `app/Http/Controllers/BeritaController.php`

- [ ] **Step 1: Create the controller**

```php
<?php

namespace App\Http\Controllers;

use App\Models\Berita;

class BeritaController extends Controller
{
    public function index()
    {
        $beritas = Berita::published()
            ->orderByDesc('tanggal')
            ->get();

        return view('berita', compact('beritas'));
    }

    public function show(string $slug)
    {
        $berita = Berita::published()
            ->where('slug', $slug)
            ->firstOrFail();

        return view('berita-detail', compact('berita'));
    }
}
```

- [ ] **Step 2: Commit**

```bash
git add app/Http/Controllers/BeritaController.php
git commit -m "feat: add BeritaController with index and show"
```

---

### Task 5: Update Routes

**Files:**
- Modify: `routes/web.php:44-46`

- [ ] **Step 1: Replace the berita closure route with controller routes**

Find in `routes/web.php`:
```php
Route::get('/berita', function () {
    return view('berita');
});
```

Replace with:
```php
Route::get('/berita', [\App\Http\Controllers\BeritaController::class, 'index']);
Route::get('/berita/{slug}', [\App\Http\Controllers\BeritaController::class, 'show'])->name('berita.show');
```

- [ ] **Step 2: Add the use statement at the top of web.php**

Add to the imports at the top (after existing use statements):
```php
use App\Http\Controllers\BeritaController;
```

Then update the routes to use the short class reference:
```php
Route::get('/berita', [BeritaController::class, 'index']);
Route::get('/berita/{slug}', [BeritaController::class, 'show'])->name('berita.show');
```

- [ ] **Step 3: Verify routes**

Run: `php artisan route:list --path=berita`
Expected: Two routes — `GET berita` and `GET berita/{slug}`

- [ ] **Step 4: Commit**

```bash
git add routes/web.php
git commit -m "feat: update berita routes to use controller"
```

---

### Task 6: Update berita.blade.php (Listing Page)

**Files:**
- Modify: `resources/views/berita.blade.php`

- [ ] **Step 1: Replace the entire file content**

Replace `resources/views/berita.blade.php` with:

```blade
@php
    $heroJudul = 'Berita';
    $heroDeskripsi = 'Informasi dan berita terkini seputar kegiatan Patriot Metric';
@endphp

<x-layouts.app>
    <div class="bg-white min-h-screen">
        {{-- Hero --}}
        <section class="bg-[#1B5E20]">
            <div class="max-w-[900px] mx-auto px-6 md:px-8 py-20 md:py-28 text-center">
                <h1 class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[34px] md:text-[50px] text-white leading-tight">{{ $heroJudul }}</h1>
                <p class="mt-4 font-['Plus_Jakarta_Sans',sans-serif] text-[16px] md:text-[19px] leading-[28px] text-white/85 max-w-[600px] mx-auto">
                    {{ $heroDeskripsi }}
                </p>
            </div>
        </section>

        {{-- Berita List --}}
        @if($beritas->isNotEmpty())
            <div class="max-w-[1100px] mx-auto px-6 md:px-8 py-10">
                <div class="divide-y divide-[#e2e8f0]">
                    @foreach($beritas as $item)
                        <a href="{{ route('berita.show', $item->slug) }}" class="flex flex-col md:flex-row gap-5 md:gap-8 py-8 first:pt-0 last:pb-0 group">
                            {{-- Thumbnail --}}
                            <div class="w-full md:w-[240px] h-[160px] md:h-[140px] shrink-0 rounded-lg overflow-hidden bg-[#f1f5f9]">
                                @if($item->gambar)
                                    <img src="{{ url('cms-assets/' . $item->gambar) }}" alt="{{ $item->judul }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-[#f1f5f9]">
                                        <span class="text-[#94a3b8] text-[13px]">Gambar</span>
                                    </div>
                                @endif
                            </div>
                            {{-- Text --}}
                            <div class="flex-1 min-w-0">
                                <span class="font-['Plus_Jakarta_Sans',sans-serif] text-[13px] text-[#94a3b8]">
                                    {{ $item->tanggal->translatedFormat('j F Y') }}
                                </span>
                                <h2 class="mt-1 font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[18px] md:text-[20px] leading-[28px] text-[#1d293d] group-hover:text-[#1B5E20] transition-colors">{{ $item->judul }}</h2>
                                <p class="mt-2 font-['Plus_Jakarta_Sans',sans-serif] text-[15px] leading-[25px] text-[#64748b] line-clamp-2">{{ $item->excerpt }}</p>
                                <span class="inline-block mt-3 font-['Plus_Jakarta_Sans',sans-serif] font-medium text-[14px] text-[#1B5E20] group-hover:underline">Baca selengkapnya &rarr;</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @else
            <section class="py-16 bg-[#f8fafc]">
                <div class="max-w-[1100px] mx-auto px-6 md:px-8">
                    <div class="text-center py-20">
                        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-[#1B5E20]/10 mb-6">
                            <i data-lucide="newspaper" class="w-10 h-10 text-[#1B5E20]"></i>
                        </div>
                        <h2 class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[24px] text-[#1d293d] mb-3">Belum Ada Berita</h2>
                        <p class="font-['Plus_Jakarta_Sans',sans-serif] font-normal text-[16px] text-[#45556c] max-w-[480px] mx-auto">
                            Belum ada berita yang dipublikasikan. Nantikan informasi dan berita terbaru dari Patriot Metric.
                        </p>
                    </div>
                </div>
            </section>
        @endif
    </div>
</x-layouts.app>
```

- [ ] **Step 2: Commit**

```bash
git add resources/views/berita.blade.php
git commit -m "feat: update berita listing to use Berita model"
```

---

### Task 7: Create berita-detail.blade.php (Detail Page)

**Files:**
- Create: `resources/views/berita-detail.blade.php`

- [ ] **Step 1: Create the detail view**

```blade
<x-layouts.app>
    <div class="bg-white min-h-screen">
        {{-- Hero with image --}}
        <section class="bg-[#1B5E20]">
            <div class="max-w-[900px] mx-auto px-6 md:px-8 py-16 md:py-24 text-center">
                <a href="{{ url('/berita') }}" class="inline-flex items-center gap-1 font-['Plus_Jakarta_Sans',sans-serif] text-[14px] text-white/70 hover:text-white mb-6 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
                    Kembali ke Berita
                </a>
                <h1 class="font-['Plus_Jakarta_Sans',sans-serif] font-bold text-[28px] md:text-[42px] text-white leading-tight">{{ $berita->judul }}</h1>
                <p class="mt-4 font-['Plus_Jakarta_Sans',sans-serif] text-[15px] text-white/70">
                    {{ $berita->tanggal->translatedFormat('j F Y') }}
                </p>
            </div>
        </section>

        {{-- Featured Image --}}
        @if($berita->gambar)
            <div class="max-w-[900px] mx-auto px-6 md:px-8 -mt-8">
                <div class="rounded-xl overflow-hidden shadow-lg">
                    <img src="{{ url('cms-assets/' . $berita->gambar) }}" alt="{{ $berita->judul }}" class="w-full h-[300px] md:h-[450px] object-cover">
                </div>
            </div>
        @endif

        {{-- Content --}}
        <article class="max-w-[750px] mx-auto px-6 md:px-8 py-12">
            <div class="font-['Plus_Jakarta_Sans',sans-serif] text-[16px] leading-[30px] text-[#334155] prose prose-lg max-w-none prose-headings:font-['Plus_Jakarta_Sans',sans-serif] prose-headings:text-[#1d293d] prose-a:text-[#1B5E20]">
                {!! $berita->konten !!}
            </div>
        </article>

        {{-- Back link --}}
        <div class="max-w-[750px] mx-auto px-6 md:px-8 pb-16">
            <a href="{{ url('/berita') }}" class="inline-flex items-center gap-2 font-['Plus_Jakarta_Sans',sans-serif] font-medium text-[14px] text-[#1B5E20] hover:underline">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
                Kembali ke daftar berita
            </a>
        </div>
    </div>
</x-layouts.app>
```

- [ ] **Step 2: Commit**

```bash
git add resources/views/berita-detail.blade.php
git commit -m "feat: add berita detail page view"
```

---

### Task 8: Remove Old CMS Berita Page (Cleanup)

**Files:**
- Delete: `app/Filament/Pages/CmsComproBerita.php`
- Delete: `app/Filament/Pages/ComproForms/BeritaForm.php`

- [ ] **Step 1: Delete the old CMS page files**

Run (PowerShell):
```powershell
Remove-Item app/Filament/Pages/CmsComproBerita.php
Remove-Item app/Filament/Pages/ComproForms/BeritaForm.php
```

- [ ] **Step 2: Remove 'berita.daftar' from ComproContentService REPEATER_KEYS**

In `app/Services/ComproContentService.php`, find line 22:
```php
    private const REPEATER_KEYS = [
        'institusi.daftar_baris_1', 'institusi.daftar_baris_2',
        'timeline.daftar', 'instagram.posts', 'tujuan-utama.daftar',
        'misi.daftar', 'team-grid.daftar', 'daftar-penerima.daftar',
        'steps.daftar', 'faq.daftar', 'artikel.daftar', 'berita.daftar',
    ];
```

Remove `'berita.daftar'` from the array:
```php
    private const REPEATER_KEYS = [
        'institusi.daftar_baris_1', 'institusi.daftar_baris_2',
        'timeline.daftar', 'instagram.posts', 'tujuan-utama.daftar',
        'misi.daftar', 'team-grid.daftar', 'daftar-penerima.daftar',
        'steps.daftar', 'faq.daftar', 'artikel.daftar',
    ];
```

- [ ] **Step 3: Commit**

```bash
git add -A
git commit -m "chore: remove old CMS berita page in favor of BeritaResource"
```

---

### Task 9: Verify in Browser

- [ ] **Step 1: Start the dev server**

Run: `php artisan serve`

- [ ] **Step 2: Visit admin panel and create a test berita**

Navigate to Filament admin → Berita → Create. Fill in:
- Judul: "Test Berita Pertama"
- Excerpt: "Ini adalah ringkasan berita pertama"
- Konten: Some rich text content
- Gambar: Upload a test image
- Tanggal: Today
- Publikasikan: ON

- [ ] **Step 3: Visit http://127.0.0.1:8000/berita**

Expected: The listing page shows the test article with thumbnail, date, title, and excerpt.

- [ ] **Step 4: Click the article**

Expected: Navigates to `/berita/{slug}` showing the full article with featured image, title, date, and rich text content.

- [ ] **Step 5: Verify back navigation**

Click "Kembali ke daftar berita" link. Expected: Returns to `/berita` listing.
