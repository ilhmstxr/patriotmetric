<?php

namespace App\Filament\Pages;

use App\Filament\Pages\ComproForms\TimForm;
use Filament\Support\Icons\Heroicon;
use BackedEnum;

class CmsComproTim extends CmsCompro
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;
    protected static ?string $navigationLabel = 'Tim';
    protected static ?int $navigationSort = 4;
    protected static string $comproPage = 'tim';
    protected static string $formSchemaClass = TimForm::class;

    public static function getSlug(): string
    {
        return 'cms-compro/tim';
    }
}
