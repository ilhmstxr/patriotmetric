<?php

namespace App\Filament\Resources\Pengumpulans\Pages;

use App\Filament\Resources\Pengumpulans\PengumpulanResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPengumpulan extends ViewRecord
{
    protected static string $resource = PengumpulanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
