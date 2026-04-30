<?php

namespace App\Filament\Resources\PengaturanCms\Pages;

use App\Filament\Resources\PengaturanCms\PengaturanCmsResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePengaturanCms extends CreateRecord
{
    protected static string $resource = PengaturanCmsResource::class;

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        $dto = new \App\DTO\PengaturanCmsDTO($data);
        return app(\App\Services\PengaturanCmsService::class)->store($dto);
    }
}
