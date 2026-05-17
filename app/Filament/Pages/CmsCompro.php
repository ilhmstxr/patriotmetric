<?php

namespace App\Filament\Pages;

use App\Filament\Pages\ComproForms\PanduanForm;
use App\Filament\Pages\ComproForms\PenghargaanForm;
use App\Filament\Pages\ComproForms\PengumumanForm;
use App\Filament\Pages\ComproForms\ProfileForm;
use App\Filament\Pages\ComproForms\TimForm;
use App\Filament\Pages\ComproForms\VisiMisiForm;
use App\Filament\Pages\ComproForms\WelcomeForm;
use App\Services\ComproContentService;
use App\Services\ImageProcessingService;
use BackedEnum;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

class CmsCompro extends Page
{
    use InteractsWithForms;

    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedGlobeAlt;

    protected static ?string $navigationLabel = 'CMS Compro';

    protected static string | \UnitEnum | null $navigationGroup = 'Konten Website';

    protected static ?int $navigationSort = 1;

    protected string $view = 'filament.pages.cms-compro';

    public ?array $data = [];

    public string $activeTab = 'welcome';

    public bool $showPreview = true;

    /**
     * Map of tab names to their form schema classes.
     */
    protected function getFormClassMap(): array
    {
        return [
            'welcome' => WelcomeForm::class,
            'profile' => ProfileForm::class,
            'visi-misi' => VisiMisiForm::class,
            'tim' => TimForm::class,
            'penghargaan' => PenghargaanForm::class,
            'panduan' => PanduanForm::class,
            'pengumuman' => PengumumanForm::class,
        ];
    }

    public function mount(): void
    {
        try {
            $this->loadFormData();
        } catch (\Throwable $e) {
            Log::error('CmsCompro mount failed', ['error' => $e->getMessage()]);
            // Continue with empty form
        }
    }

    public function updatedActiveTab(): void
    {
        try {
            $this->loadFormData();
        } catch (\Throwable $e) {
            Log::error('CmsCompro updatedActiveTab failed', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Load content from database for the active tab and fill the form.
     */
    protected function loadFormData(): void
    {
        $service = app(ComproContentService::class);
        $pageContent = $service->getPageContent($this->activeTab);

        $formData = [];

        foreach ($pageContent as $section => $items) {
            foreach ($items as $item) {
                $key = "{$section}.{$item->key}";
                $formData[$key] = $item->value;
            }
        }

        $this->form->fill($formData);
    }

    /**
     * Save form data: separate static fields from repeater fields,
     * process images, persist to database, and refresh preview.
     */
    public function save(): void
    {
        $data = $this->form->getState();

        try {
            $service = app(ComproContentService::class);
            $imageService = app(ImageProcessingService::class);

            $staticData = [];
            $repeaterData = [];

            // Separate static fields from repeater fields
            foreach ($data as $sectionKey => $value) {
                if (is_array($value) && $this->isRepeaterField($sectionKey)) {
                    // Process images within repeater items
                    $processedItems = $this->processRepeaterImages($value, $imageService);
                    $repeaterData[$sectionKey] = $processedItems;
                } elseif ($this->isImageField($sectionKey, $value)) {
                    // Process single image upload
                    $staticData[$sectionKey] = $this->processImageUpload($value, $sectionKey, $imageService);
                } else {
                    $staticData[$sectionKey] = $value;
                }
            }

            // Persist static content
            if (! empty($staticData)) {
                $service->updateStaticContent($this->activeTab, $staticData);
            }

            // Persist repeater content
            foreach ($repeaterData as $sectionKey => $items) {
                [$section, $key] = explode('.', $sectionKey, 2);
                $service->updateRepeaterContent($this->activeTab, $section, $key, $items);
            }

            // Clear cache for this page
            $service->clearCache($this->activeTab);

            // Dispatch event to refresh preview iframe
            $this->dispatch('content-saved');

            Notification::make()
                ->title('Konten berhasil disimpan')
                ->success()
                ->send();
        } catch (\Throwable $e) {
            Log::error('CmsCompro save failed', [
                'page' => $this->activeTab,
                'error' => $e->getMessage(),
            ]);

            Notification::make()
                ->title('Gagal menyimpan konten')
                ->body('Terjadi kesalahan saat menyimpan. Silakan coba lagi.')
                ->danger()
                ->send();
        }
    }

    /**
     * Configure the form schema dynamically based on active tab.
     */
    public function form(Schema $schema): Schema
    {
        $formClassMap = $this->getFormClassMap();
        $formClass = $formClassMap[$this->activeTab] ?? WelcomeForm::class;

        return $schema
            ->components($formClass::schema())
            ->statePath('data');
    }

    /**
     * Determine if a field key represents a repeater field.
     */
    protected function isRepeaterField(string $sectionKey): bool
    {
        $repeaterKeys = [
            'institusi.daftar_baris_1',
            'institusi.daftar_baris_2',
            'timeline.daftar',
            'instagram.posts',
            'tujuan-utama.daftar',
            'misi.daftar',
            'team-grid.daftar',
            'daftar-penerima.daftar',
            'steps.daftar',
            'faq.daftar',
            'artikel.daftar',
        ];

        return in_array($sectionKey, $repeaterKeys);
    }

    /**
     * Determine if a field value represents an image upload.
     */
    protected function isImageField(string $sectionKey, mixed $value): bool
    {
        $imageKeys = [
            'hero.background_image',
        ];

        if (in_array($sectionKey, $imageKeys)) {
            return true;
        }

        // Check if value is an UploadedFile instance
        if ($value instanceof UploadedFile) {
            return true;
        }

        return false;
    }

    /**
     * Process a single image upload field.
     */
    protected function processImageUpload(mixed $value, string $sectionKey, ImageProcessingService $imageService): ?string
    {
        if ($value instanceof UploadedFile) {
            // Get existing path to replace
            $service = app(ComproContentService::class);
            [$section, $key] = explode('.', $sectionKey, 2);
            $existingPath = $service->getValue($this->activeTab, $section, $key);

            return $imageService->processAndStore($value, $existingPath);
        }

        // If it's already a string path (existing image not changed), return as-is
        return is_string($value) ? $value : null;
    }

    /**
     * Process images within repeater items.
     */
    protected function processRepeaterImages(array $items, ImageProcessingService $imageService): array
    {
        $processed = [];

        foreach ($items as $index => $item) {
            if (! is_array($item)) {
                $processed[$index] = $item;
                continue;
            }

            foreach ($item as $fieldKey => $fieldValue) {
                if ($fieldValue instanceof UploadedFile) {
                    try {
                        $item[$fieldKey] = $imageService->processAndStore($fieldValue);
                    } catch (\Throwable $e) {
                        Log::error('Repeater image processing failed', [
                            'field' => $fieldKey,
                            'error' => $e->getMessage(),
                        ]);
                        // Keep existing value or null
                        $item[$fieldKey] = null;
                    }
                }
            }

            $processed[$index] = $item;
        }

        return array_values($processed);
    }

    public function getTitle(): string | Htmlable
    {
        return 'CMS Company Profile';
    }
}
