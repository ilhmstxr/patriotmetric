<?php

namespace App\Filament\Pages;

use App\Filament\Pages\ComproForms\PengumumanForm;
use Filament\Support\Icons\Heroicon;
use BackedEnum;

class CmsComproPengumuman extends CmsCompro
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMegaphone;
    protected static ?string $navigationLabel = 'Pengumuman';
    protected static ?int $navigationSort = 7;
    protected static string $comproPage = 'pengumuman';
    protected static string $formSchemaClass = PengumumanForm::class;

    public static function getSlug(?\Filament\Panel $panel = null): string
    {
        return 'cms-compro/pengumuman';
    }
}
