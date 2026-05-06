<?php

namespace App\Filament\Resources\Reviewers\Schemas;

use App\Models\Reviewer;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Validation\Rule;

class ReviewerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->rule(function (?Reviewer $record) {
                        return Rule::unique('users', 'email')
                            ->ignore($record?->user_id);
                    })
                    ->helperText('Email akan dipakai reviewer untuk login.'),
                TextInput::make('nama_lengkap')
                    ->label('Nama Lengkap')
                    ->required()
                    ->maxLength(255),
                TextInput::make('nip')
                    ->label('NIP / NIDN')
                    ->maxLength(255)
                    ->helperText('Opsional.'),
                TextInput::make('password')
                    ->label('Password')
                    ->password()
                    ->revealable()
                    ->maxLength(255)
                    ->dehydrated(fn ($state) => filled($state))
                    ->helperText(fn (string $operation): string => $operation === 'create'
                        ? 'Kosongkan untuk men-generate password otomatis (akan ditampilkan setelah disimpan).'
                        : 'Kosongkan jika tidak ingin mengganti password.'),
            ]);
    }
}
