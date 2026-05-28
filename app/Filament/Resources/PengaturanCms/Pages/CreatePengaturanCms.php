<?php

namespace App\Filament\Resources\PengaturanCms\Pages;

use App\DTO\PengaturanCmsDTO;
use App\Filament\Resources\PengaturanCms\PengaturanCmsResource;
use App\Services\PengaturanCmsService;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreatePengaturanCms extends CreateRecord
{
    protected static string $resource = PengaturanCmsResource::class;
 
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function handleRecordCreation(array $data): Model
    {
        $dto = new PengaturanCmsDTO($data);
        return app(PengaturanCmsService::class)->store($dto);
    }
}
