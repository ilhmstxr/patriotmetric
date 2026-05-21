<?php

namespace App\Filament\Resources\Assessments\Pages;

use App\DTO\AssessmentDTO\AssessmentDTO;
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
        return \Illuminate\Support\Facades\DB::transaction(function () use ($data) {
            // 1. Create or Find User
            $user = \App\Models\User::where('email', $data['user_email'])->first();
            if (!$user) {
                $user = \App\Models\User::create([
                    'email' => $data['user_email'],
                    'password' => \Illuminate\Support\Facades\Hash::make($data['user_password'] ?? 'password123'),
                    'role' => 'PESERTA',
                    'status' => 'ACTIVE',
                ]);
            }

            // 2. Create Institusi
            $institusi = \App\Models\Institusi::create([
                'nama_institusi' => $data['institusi_nama'],
                'jenis_institusi' => $data['institusi_jenis'],
            ]);

            // 3. Set relationship IDs
            $data['user_id'] = $user->id;
            $data['institution_id'] = $institusi->id;
            $data['tahun_periode'] = $data['tahun_periode'] ?? date('Y');

            // 4. Create Assessment via DTO & Service
            $dto = new AssessmentDTO($data);
            $assessment = app(AssessmentService::class)->store($dto);

            // 5. Create Identitas
            $assessment->identitas()->create([
                'jml_mahasiswa' => $data['identitas_jml_mahasiswa'] ?? 0,
                'jml_dosen' => $data['identitas_jml_dosen'] ?? 0,
                'jml_tendik' => $data['identitas_jml_tendik'] ?? 0,
                'jml_prodi' => $data['identitas_jml_prodi'] ?? 0,
                'jml_fakultas' => $data['identitas_jml_fakultas'] ?? 0,
                'visi' => $data['identitas_visi'] ?? null,
                'misi' => $data['identitas_misi'] ?? null,
            ]);

            return $assessment;
        });
    }

}
