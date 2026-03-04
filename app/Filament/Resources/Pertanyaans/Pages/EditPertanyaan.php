<?php

namespace App\Filament\Resources\Pertanyaans\Pages;

use App\Filament\Resources\Pertanyaans\PertanyaanResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPertanyaan extends EditRecord
{
    protected static string $resource = PertanyaanResource::class;

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
