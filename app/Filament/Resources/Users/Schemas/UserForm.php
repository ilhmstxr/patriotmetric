<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                \Filament\Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                \Filament\Forms\Components\TextInput::make('password')
                    ->password()
                    ->required(fn(string $operation): bool => $operation === 'create')
                    ->dehydrateStateUsing(fn($state) => \Illuminate\Support\Facades\Hash::make($state))
                    ->dehydrated(fn($state) => filled($state))
                    ->maxLength(255),
                \Filament\Forms\Components\Select::make('role')
                    ->options([
                        'admin' => 'Admin',
                        'reviewer' => 'Reviewer',
                        'submitter' => 'Peserta',
                    ])
                    ->required(),
                \Filament\Forms\Components\TextInput::make('nama_institusi')
                    ->maxLength(255),
                \Filament\Forms\Components\TextInput::make('telepon')
                    ->tel()
                    ->maxLength(255),
                \Filament\Forms\Components\Textarea::make('alamat')
                    ->columnSpanFull(),
            ]);
    }
}
