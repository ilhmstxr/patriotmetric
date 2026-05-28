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
