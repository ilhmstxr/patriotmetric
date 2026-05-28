<?php

namespace App\Filament\Resources\PengaturanCms\Pages;

use App\DTO\PengaturanCmsDTO;
use App\Filament\Resources\PengaturanCms\PengaturanCmsResource;
use App\Services\PengaturanCmsService;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditPengaturanCms extends EditRecord
{
    protected static string $resource = PengaturanCmsResource::class;
 
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
        $dto = new PengaturanCmsDTO($data);
        app(PengaturanCmsService::class)->update($record->getKey(), $dto);

        return $record->refresh();
    }
}
