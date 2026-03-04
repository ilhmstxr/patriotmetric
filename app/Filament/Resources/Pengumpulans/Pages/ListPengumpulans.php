<?php

namespace App\Filament\Resources\Pengumpulans\Pages;

use App\Filament\Resources\Pengumpulans\PengumpulanResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPengumpulans extends ListRecords
{
    protected static string $resource = PengumpulanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
