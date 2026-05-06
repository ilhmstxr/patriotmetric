<?php

namespace App\Filament\Resources\SubmissionTimelines\Pages;

use App\Filament\Resources\SubmissionTimelines\SubmissionTimelineResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSubmissionTimeline extends EditRecord
{
    protected static string $resource = SubmissionTimelineResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()->label('Hapus'),
        ];
    }
}
