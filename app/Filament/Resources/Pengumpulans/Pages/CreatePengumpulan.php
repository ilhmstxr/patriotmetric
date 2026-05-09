<?php

namespace App\Filament\Resources\Pengumpulans\Pages;

use App\DTO\PengumpulanDTO;
use App\Filament\Resources\Pengumpulans\PengumpulanResource;
use App\Services\PengumpulanService;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreatePengumpulan extends CreateRecord
{
    protected static string $resource = PengumpulanResource::class;
 
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function handleRecordCreation(array $data): Model
    {
        $dto = new PengumpulanDTO($data);
        return app(PengumpulanService::class)->store($dto);
    }
}
