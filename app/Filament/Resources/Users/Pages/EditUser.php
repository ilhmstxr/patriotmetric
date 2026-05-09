<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

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
            $reviewer = \App\Models\Reviewer::where('user_id', $user->id)->first();
            if ($reviewer) {
                $data['nama_lengkap'] = $reviewer->nama_lengkap;
                $data['nip'] = $reviewer->nip;
            }
        } elseif ($user->role === 'PESERTA') {
            $pengumpulan = \App\Models\Pengumpulan::with('institusi')->where('user_id', $user->id)->first();
            if ($pengumpulan) {
                $data['nama_pic'] = $pengumpulan->nama_pic;
                $data['jabatan_pic'] = $pengumpulan->jabatan_pic;
                $data['no_hp_pic'] = $pengumpulan->no_hp_pic;
                if ($pengumpulan->institusi) {
                    $data['nama_pt'] = $pengumpulan->institusi->nama_institusi;
                    $data['jenis_pt'] = $pengumpulan->institusi->jenis_institusi;
                }
            }
        }

        return $data;
    }

    protected function handleRecordUpdate(\Illuminate\Database\Eloquent\Model $record, array $data): \Illuminate\Database\Eloquent\Model
    {
        return \Illuminate\Support\Facades\DB::transaction(function () use ($record, $data) {
            $userData = [
                'email' => $data['email'],
                'role' => $data['role'],
                'status' => $data['status'],
            ];
            
            if (!empty($data['password'])) {
                $userData['password'] = \Illuminate\Support\Facades\Hash::make($data['password']);
            }

            $record->update($userData);

            if ($data['role'] === 'REVIEWER') {
                $reviewer = \App\Models\Reviewer::firstOrCreate(['user_id' => $record->id]);
                $reviewer->update([
                    'nama_lengkap' => $data['nama_lengkap'] ?? $reviewer->nama_lengkap,
                    'nip' => $data['nip'] ?? $reviewer->nip,
                ]);
            } elseif ($data['role'] === 'PESERTA') {
                $pengumpulan = \App\Models\Pengumpulan::where('user_id', $record->id)->first();
                
                if ($pengumpulan) {
                    $pengumpulan->update([
                        'nama_pic' => $data['nama_pic'] ?? $pengumpulan->nama_pic,
                        'jabatan_pic' => $data['jabatan_pic'] ?? $pengumpulan->jabatan_pic,
                        'no_hp_pic' => $data['no_hp_pic'] ?? $pengumpulan->no_hp_pic,
                    ]);
                    
                    if ($pengumpulan->institusi) {
                        $pengumpulan->institusi->update([
                            'nama_institusi' => $data['nama_pt'] ?? $pengumpulan->institusi->nama_institusi,
                            'jenis_institusi' => $data['jenis_pt'] ?? $pengumpulan->institusi->jenis_institusi,
                        ]);
                    }
                }
            }

            return $record->refresh();
        });
    }
}
