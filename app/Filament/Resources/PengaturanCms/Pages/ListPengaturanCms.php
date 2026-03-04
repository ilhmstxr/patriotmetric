<?php

namespace App\Filament\Resources\PengaturanCms\Pages;

use App\Filament\Resources\PengaturanCms\PengaturanCmsResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPengaturanCms extends ListRecords
{
    protected static string $resource = PengaturanCmsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
