<?php

namespace App\Filament\Resources\Rubriks\Pages;

use App\Filament\Resources\Rubriks\RubrikResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRubriks extends ListRecords
{
    protected static string $resource = RubrikResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
