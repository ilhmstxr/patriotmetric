<?php

namespace App\Filament\Resources\SubmissionTimelines\Pages;

use App\Filament\Resources\SubmissionTimelines\SubmissionTimelineResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSubmissionTimeline extends CreateRecord
{
    protected static string $resource = SubmissionTimelineResource::class;
 
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterCreate(): void
    {
        \Illuminate\Support\Facades\Artisan::call('app:sync-submission-statuses');
    }
}
