<?php

namespace App\Filament\Resources\Pertanyaans\Pages;

use App\Filament\Resources\Pertanyaans\PertanyaanResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPertanyaans extends ListRecords
{
    protected static string $resource = PertanyaanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
