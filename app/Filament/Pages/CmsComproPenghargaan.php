<?php

namespace App\Filament\Pages;

use App\Filament\Pages\ComproForms\PenghargaanForm;
use Filament\Support\Icons\Heroicon;
use BackedEnum;

class CmsComproPenghargaan extends CmsCompro
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTrophy;
    protected static ?string $navigationLabel = 'Penghargaan';
    protected static ?int $navigationSort = 5;
    protected static string $comproPage = 'penghargaan';
    protected static string $formSchemaClass = PenghargaanForm::class;

    public static function getSlug(?\Filament\Panel $panel = null): string
    {
        return 'cms-compro/penghargaan';
    }
}
