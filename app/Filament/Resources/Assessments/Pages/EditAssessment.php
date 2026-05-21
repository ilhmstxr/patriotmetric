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
        return \Illuminate\Support\Facades\DB::transaction(function () use ($record, $data) {
            // 1. Update User Email & Password
            $user = $record->user;
            if ($user) {
                $userUpdate = [];
                if (!empty($data['user_email'])) {
                    $userUpdate['email'] = $data['user_email'];
                }
                if (!empty($data['user_password'])) {
                    $userUpdate['password'] = \Illuminate\Support\Facades\Hash::make($data['user_password']);
                }
                if (!empty($userUpdate)) {
                    $user->update($userUpdate);
                }
            }

            // 2. Update Institusi (only if fields are present in $data)
            $institusi = $record->institusi;
            if ($institusi && (isset($data['institusi_nama']) || isset($data['institusi_jenis']))) {
                $institusi->update([
                    'nama_institusi' => $data['institusi_nama'] ?? $institusi->nama_institusi,
                    'jenis_institusi' => $data['institusi_jenis'] ?? $institusi->jenis_institusi,
                ]);
            }

            // 3. Update Identitas (only if fields are present in $data)
            $identitasFields = [
                'identitas_jml_mahasiswa' => 'jml_mahasiswa',
                'identitas_jml_dosen' => 'jml_dosen',
                'identitas_jml_tendik' => 'jml_tendik',
                'identitas_jml_prodi' => 'jml_prodi',
                'identitas_jml_fakultas' => 'jml_fakultas',
                'identitas_visi' => 'visi',
                'identitas_misi' => 'misi',
            ];
            $identitasData = [];
            foreach ($identitasFields as $formKey => $dbKey) {
                if (array_key_exists($formKey, $data)) {
                    $identitasData[$dbKey] = $data[$formKey];
                }
            }
            if (!empty($identitasData)) {
                $identitas = $record->identitas;
                if ($identitas) {
                    $identitas->update($identitasData);
                } else {
                    $record->identitas()->create($identitasData);
                }
            }

            // 4. Update Assessment DTO
            $dto = new AssessmentDTO(array_merge($record->toArray(), $data));
            app(AssessmentService::class)->update($record->getKey(), $dto);

            return $record->refresh();
        });
    }

}
