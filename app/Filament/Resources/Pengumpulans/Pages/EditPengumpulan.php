<?php

namespace App\Filament\Resources\Pengumpulans\Pages;

use App\Filament\Resources\Pengumpulans\PengumpulanResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPengumpulan extends EditRecord
{
    protected static string $resource = PengumpulanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function handleRecordUpdate(\Illuminate\Database\Eloquent\Model $record, array $data): \Illuminate\Database\Eloquent\Model
    {
        $dto = new \App\DTO\PengumpulanDTO($data);
        app(\App\Services\PengumpulanService::class)->update($record->getKey(), $dto);

        return $record->refresh();
    }
}
