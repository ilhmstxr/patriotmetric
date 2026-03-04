<?php

namespace App\Filament\Resources\Pengumpulans\Schemas;

use Filament\Schemas\Schema;

class PengumpulanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                \Filament\Forms\Components\Select::make('reviewer_id')
                    ->relationship('reviewer', 'name'),
                \Filament\Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'assigned' => 'Assigned',
                        'reviewed' => 'Reviewed',
                    ])
                    ->required(),
                \Filament\Forms\Components\TextInput::make('total_skor_sistem')
                    ->numeric()
                    ->default(0),
                \Filament\Forms\Components\TextInput::make('total_skor_akhir')
                    ->numeric()
                    ->default(0),
            ]);
    }
}
