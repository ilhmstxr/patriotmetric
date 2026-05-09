<?php

namespace App\Filament\Resources\Pengumpulans\Pages;

use App\DTO\PengumpulanDTO;
use App\Filament\Resources\Pengumpulans\PengumpulanResource;
use App\Services\PengumpulanService;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditPengumpulan extends EditRecord
{
    protected static string $resource = PengumpulanResource::class;
 
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

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $dto = new PengumpulanDTO($data);
        app(PengumpulanService::class)->update($record->getKey(), $dto);

        return $record->refresh();
    }
}
