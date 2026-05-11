<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use App\Models\Assessment;
use App\Models\Reviewer;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;
 
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

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $user = $this->getRecord();
        
        if ($user->role === 'REVIEWER') {
            $reviewer = Reviewer::where('user_id', $user->id)->first();
            if ($reviewer) {
                $data['nama_lengkap'] = $reviewer->nama_lengkap;
                $data['nip'] = $reviewer->nip;
            }
        } elseif ($user->role === 'PESERTA') {
            $Assessment = Assessment::with('institusi')->where('user_id', $user->id)->first();
            if ($Assessment) {
                $data['nama_pic'] = $Assessment->nama_pic;
                $data['jabatan_pic'] = $Assessment->jabatan_pic;
                $data['no_hp_pic'] = $Assessment->no_hp_pic;
                if ($Assessment->institusi) {
                    $data['nama_pt'] = $Assessment->institusi->nama_institusi;
                    $data['jenis_pt'] = $Assessment->institusi->jenis_institusi;
                }
            }
        }

        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        return DB::transaction(function () use ($record, $data) {
            $userData = [
                'email' => $data['email'],
                'role' => $data['role'],
                'status' => $data['status'],
            ];
            
            if (!empty($data['password'])) {
                $userData['password'] = Hash::make($data['password']);
            }

            $record->update($userData);

            if ($data['role'] === 'REVIEWER') {
                $reviewer = Reviewer::firstOrCreate(['user_id' => $record->id]);
                $reviewer->update([
                    'nama_lengkap' => $data['nama_lengkap'] ?? $reviewer->nama_lengkap,
                    'nip' => $data['nip'] ?? $reviewer->nip,
                ]);
            } elseif ($data['role'] === 'PESERTA') {
                $Assessment = Assessment::where('user_id', $record->id)->first();
                
                if ($Assessment) {
                    $Assessment->update([
                        'nama_pic' => $data['nama_pic'] ?? $Assessment->nama_pic,
                        'jabatan_pic' => $data['jabatan_pic'] ?? $Assessment->jabatan_pic,
                        'no_hp_pic' => $data['no_hp_pic'] ?? $Assessment->no_hp_pic,
                    ]);
                    
                    if ($Assessment->institusi) {
                        $Assessment->institusi->update([
                            'nama_institusi' => $data['nama_pt'] ?? $Assessment->institusi->nama_institusi,
                            'jenis_institusi' => $data['jenis_pt'] ?? $Assessment->institusi->jenis_institusi,
                        ]);
                    }
                }
            }

            return $record->refresh();
        });
    }
}
