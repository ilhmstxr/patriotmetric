<?php

namespace App\Filament\Resources\Assessments\Pages;

use App\DTO\AssessmentDTO\AssessmentDTO;
use App\Filament\Resources\Assessments\AssessmentResource;
use App\Services\AssessmentService;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditAssessment extends EditRecord
{
    protected static string $resource = AssessmentResource::class;
 
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
        // Gabungkan data lama dengan data baru agar DTO tidak kosong untuk field yang tidak ada di form
        $dto = new AssessmentDTO(array_merge($record->toArray(), $data));
        
        app(AssessmentService::class)->update($record->getKey(), $dto);

        return $record->refresh();
    }
}
