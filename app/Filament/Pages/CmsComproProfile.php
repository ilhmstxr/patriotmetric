<?php

namespace App\Filament\Pages;

use App\Filament\Pages\ComproForms\ProfileForm;
use Filament\Support\Icons\Heroicon;
use BackedEnum;

class CmsComproProfile extends CmsCompro
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice;
    protected static ?string $navigationLabel = 'Profile';
    protected static ?int $navigationSort = 2;
    protected static string $comproPage = 'profile';
    protected static string $formSchemaClass = ProfileForm::class;

    public static function getSlug(): string
    {
        return 'cms-compro/profile';
    }
}
