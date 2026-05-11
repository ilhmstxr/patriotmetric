<?php

namespace App\Filament\Resources\Assessments\Pages;

use App\DTO\AssessmentDTO;
use App\Filament\Resources\Assessments\AssessmentResource;
use App\Services\AssessmentService;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateAssessment extends CreateRecord
{
    protected static string $resource = AssessmentResource::class;
 
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function handleRecordCreation(array $data): Model
    {
        $dto = new AssessmentDTO($data);
        return app(AssessmentService::class)->store($dto);
    }
}
