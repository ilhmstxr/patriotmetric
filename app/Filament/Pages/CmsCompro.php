<?php

namespace App\Filament\Pages;

use App\Filament\Pages\ComproForms\WelcomeForm;
use App\Services\ComproContentService;
use BackedEnum;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;
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

    protected static string $comproPage = 'welcome';

    protected static string $formSchemaClass = WelcomeForm::class;

    public static function getSlug(?\Filament\Panel $panel = null): string
    {
        return 'cms-compro/' . static::$comproPage;
    }

    public function mount(): void
    {
        try {
            $formData = app(ComproContentService::class)->loadFormData(static::$comproPage);
            $this->form->fill($formData);
        } catch (\Throwable $e) {
            Log::error('CmsCompro mount failed', ['page' => static::$comproPage, 'error' => $e->getMessage()]);
        }
    }

    public function save(): void
    {
        try {
            $rawData = $this->form->getState();
            app(ComproContentService::class)->saveFormData(static::$comproPage, $rawData);

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
}
