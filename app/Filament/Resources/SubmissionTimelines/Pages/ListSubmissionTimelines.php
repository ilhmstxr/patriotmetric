<?php

namespace App\Filament\Resources\SubmissionTimelines\Pages;

use App\Filament\Resources\SubmissionTimelines\SubmissionTimelineResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSubmissionTimelines extends ListRecords
{
    protected static string $resource = SubmissionTimelineResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Tambah Timeline'),
        ];
    }
}
