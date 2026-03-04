<?php

namespace App\Filament\Resources\PengaturanCms\Pages;

use App\Filament\Resources\PengaturanCms\PengaturanCmsResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPengaturanCms extends EditRecord
{
    protected static string $resource = PengaturanCmsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function handleRecordUpdate(\Illuminate\Database\Eloquent\Model $record, array $data): \Illuminate\Database\Eloquent\Model
    {
        $dto = new \App\DTOs\PengaturanCmsDTO($data);
        app(\App\Services\PengaturanCmsService::class)->update($record->getKey(), $dto);

        return $record->refresh();
    }
}
