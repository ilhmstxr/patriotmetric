<?php

namespace App\Filament\Resources\Pengumpulans\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PengumpulanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Select::make('reviewer_id')
                    ->relationship('reviewer', 'name'),
                Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'assigned' => 'Assigned',
                        'reviewed' => 'Reviewed',
                    ])
                    ->required(),
                TextInput::make('total_skor_sistem')
                    ->numeric()
                    ->default(0),
                TextInput::make('total_skor_akhir')
                    ->numeric()
                    ->default(0),
            ]);
    }
}
