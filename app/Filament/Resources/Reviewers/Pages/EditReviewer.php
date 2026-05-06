<?php

namespace App\Filament\Resources\Reviewers\Pages;

use App\Filament\Resources\Reviewers\ReviewerResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EditReviewer extends EditRecord
{
    protected static string $resource = ReviewerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()->label('Hapus Reviewer'),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['email'] = $this->record->user?->email;
        $data['password'] = null;

        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        DB::transaction(function () use ($record, $data): void {
            if ($record->user) {
                $userData = ['email' => $data['email']];
                if (! empty($data['password'])) {
                    $userData['password'] = Hash::make($data['password']);
                }
                $record->user->update($userData);
            }

            $record->update([
                'nama_lengkap' => $data['nama_lengkap'],
                'nip' => $data['nip'] ?? null,
            ]);
        });

        return $record->refresh();
    }
}
