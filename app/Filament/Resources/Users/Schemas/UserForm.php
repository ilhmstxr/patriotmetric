<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('email')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                TextInput::make('password')
                    ->password()
                    ->required(fn(string $operation): bool => $operation === 'create')
                    ->dehydrateStateUsing(fn($state) => \Illuminate\Support\Facades\Hash::make($state))
                    ->dehydrated(fn($state) => filled($state))
                    ->maxLength(255),
                Select::make('role')
                    ->options([
                        'ADMIN' => 'Admin',
                        'REVIEWER' => 'Reviewer',
                        'PESERTA' => 'Peserta',
                    ])
                    ->required(),
                Select::make('status')
                    ->options([
                        'PENDING' => 'Pending',
                        'ACTIVE' => 'Active',
                        'SUSPENDED' => 'Suspended',
                    ])
                    ->required(),
            ]);
    }
}
