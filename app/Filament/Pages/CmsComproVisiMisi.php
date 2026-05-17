<?php

namespace App\Filament\Pages;

use App\Filament\Pages\ComproForms\VisiMisiForm;
use Filament\Support\Icons\Heroicon;
use BackedEnum;

class CmsComproVisiMisi extends CmsCompro
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedEye;
    protected static ?string $navigationLabel = 'Visi & Misi';
    protected static ?int $navigationSort = 3;
    protected static string $comproPage = 'visi-misi';
    protected static string $formSchemaClass = VisiMisiForm::class;

    public static function getSlug(?\Filament\Panel $panel = null): string
    {
        return 'cms-compro/visi-misi';
    }
}
