<?php

namespace App\Filament\Pages;

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

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedGlobeAlt;

    protected static ?string $navigationLabel = 'Welcome';

    protected static string|\UnitEnum|null $navigationGroup = 'CMS Compro';

    protected static ?int $navigationSort = 1;

    protected string $view = 'filament.pages.cms-compro';

    public ?array $data = [];

    /**
     * The compro page slug this CMS page manages.
     */
    protected static string $comproPage = 'welcome';

    /**
     * The form schema class for this page.
     */
    protected static string $formSchemaClass = WelcomeForm::class;

    public static function getSlug(?\Filament\Panel $panel = null): string
    {
        return 'cms-compro/' . static::$comproPage;
    }

    public function mount(): void
    {
        try {
            $this->loadFormData();
        } catch (\Throwable $e) {
            Log::error('CmsCompro mount failed', ['page' => static::$comproPage, 'error' => $e->getMessage()]);
        }
    }

    protected function loadFormData(): void
    {
        $service = app(ComproContentService::class);
        $pageContent = $service->getPageContent(static::$comproPage);

        $formData = [];

        foreach ($pageContent as $section => $items) {
            foreach ($items as $item) {
                $key = "{$section}.{$item->key}";
                $value = $item->value;

                if ($item->type === 'image' && is_string($value) && $value !== '') {
                    $value = [$value];
                }

                if ($item->type === 'repeater' && is_array($value)) {
                    $value = $this->convertRepeaterImageStringsToArrays($value);
                }

                data_set($formData, $key, $value);
            }
        }

        Log::info('CmsCompro loadFormData', [
            'page' => static::$comproPage,
            'keys' => array_keys($formData),
            'sample' => array_slice($formData, 0, 3, true),
        ]);

        $this->data = $formData;
    }

    protected function convertRepeaterImageStringsToArrays(array $items): array
    {
        $imageKeys = ['foto', 'logo', 'gambar', 'background_image'];

        return array_map(function ($item) use ($imageKeys) {
            if (!is_array($item)) {
                return $item;
            }
            foreach ($item as $fieldKey => $fieldValue) {
                if (in_array($fieldKey, $imageKeys) && is_string($fieldValue) && $fieldValue !== '') {
                    $item[$fieldKey] = [$fieldValue];
                }
            }
            return $item;
        }, $items);
    }

    public function save(): void
    {
        $rawData = $this->form->getState();

        $data = [];
        foreach ($rawData as $section => $fields) {
            if (is_array($fields)) {
                foreach ($fields as $key => $value) {
                    $data["{$section}.{$key}"] = $value;
                }
            } else {
                $data[$section] = $fields;
            }
        }

        try {
            $service = app(ComproContentService::class);
            $imageService = app(ImageProcessingService::class);

            $staticData = [];
            $repeaterData = [];

            foreach ($data as $sectionKey => $value) {
                if (is_array($value) && $this->isRepeaterField($sectionKey)) {
                    $processedItems = $this->processRepeaterImages($value, $imageService);
                    $repeaterData[$sectionKey] = $processedItems;
                } elseif ($this->isImageField($sectionKey, $value)) {
                    $staticData[$sectionKey] = $this->processImageUpload($value, $sectionKey, $imageService);
                } else {
                    $staticData[$sectionKey] = $value;
                }
            }

            if (!empty($staticData)) {
                $service->updateStaticContent(static::$comproPage, $staticData);
            }

            foreach ($repeaterData as $sectionKey => $items) {
                [$section, $key] = explode('.', $sectionKey, 2);
                $service->updateRepeaterContent(static::$comproPage, $section, $key, $items);
            }

            $service->clearCache(static::$comproPage);
            $this->dispatch('content-saved');

            Notification::make()
                ->title('Konten berhasil disimpan')
                ->success()
                ->send();
        } catch (\Throwable $e) {
            Log::error('CmsCompro save failed', [
                'page' => static::$comproPage,
                'error' => $e->getMessage(),
            ]);

            Notification::make()
                ->title('Gagal menyimpan konten')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components(static::$formSchemaClass::schema())
            ->statePath('data');
    }

    public function getPreviewUrl(): string
    {
        if (\Illuminate\Support\Facades\Route::has('compro.preview')) {
            return route('compro.preview', ['page' => static::$comproPage]);
        }
        return '#';
    }

    public function getTitle(): string|Htmlable
    {
        return 'CMS ' . ucfirst(str_replace('-', ' ', static::$comproPage));
    }

    protected function isRepeaterField(string $sectionKey): bool
    {
        $repeaterKeys = [
            'institusi.daftar_baris_1', 'institusi.daftar_baris_2',
            'timeline.daftar', 'instagram.posts', 'tujuan-utama.daftar',
            'misi.daftar', 'team-grid.daftar', 'daftar-penerima.daftar',
            'steps.daftar', 'faq.daftar', 'artikel.daftar',
        ];
        return in_array($sectionKey, $repeaterKeys);
    }

    protected function isImageField(string $sectionKey, mixed $value): bool
    {
        if (in_array($sectionKey, ['hero.background_image'])) {
            return true;
        }
        return $value instanceof UploadedFile;
    }

    protected function processImageUpload(mixed $value, string $sectionKey, ImageProcessingService $imageService): ?string
    {
        if ($value instanceof UploadedFile) {
            $service = app(ComproContentService::class);
            [$section, $key] = explode('.', $sectionKey, 2);
            $existingPath = $service->getValue(static::$comproPage, $section, $key);
            return $imageService->processAndStore($value, $existingPath);
        }
        return is_string($value) ? $value : null;
    }

    protected function processRepeaterImages(array $items, ImageProcessingService $imageService): array
    {
        $processed = [];
        foreach ($items as $index => $item) {
            if (!is_array($item)) {
                $processed[$index] = $item;
                continue;
            }
            foreach ($item as $fieldKey => $fieldValue) {
                if ($fieldValue instanceof UploadedFile) {
                    try {
                        $item[$fieldKey] = $imageService->processAndStore($fieldValue);
                    } catch (\Throwable $e) {
                        $item[$fieldKey] = null;
                    }
                }
            }
            $processed[$index] = $item;
        }
        return array_values($processed);
    }
}
