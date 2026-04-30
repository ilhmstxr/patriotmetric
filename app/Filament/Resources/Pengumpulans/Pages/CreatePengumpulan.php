<?php

namespace App\Filament\Resources\Pengumpulans\Pages;

use App\Filament\Resources\Pengumpulans\PengumpulanResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePengumpulan extends CreateRecord
{
    protected static string $resource = PengumpulanResource::class;

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        $dto = new \App\DTO\PengumpulanDTO($data);
        return app(\App\Services\PengumpulanService::class)->store($dto);
    }
}
