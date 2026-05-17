<?php

namespace App\Filament\Pages;

use App\Filament\Pages\ComproForms\PanduanForm;
use Filament\Support\Icons\Heroicon;
use BackedEnum;

class CmsComproPanduan extends CmsCompro
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBookOpen;
    protected static ?string $navigationLabel = 'Panduan';
    protected static ?int $navigationSort = 6;
    protected static string $comproPage = 'panduan';
    protected static string $formSchemaClass = PanduanForm::class;

    public static function getSlug(?\Filament\Panel $panel = null): string
    {
        return 'cms-compro/panduan';
    }
}
