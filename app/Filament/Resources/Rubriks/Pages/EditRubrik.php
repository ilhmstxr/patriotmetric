<?php

namespace App\Filament\Resources\Rubriks\Pages;

use App\Filament\Resources\Rubriks\RubrikResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditRubrik extends EditRecord
{
    protected static string $resource = RubrikResource::class;
 
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
