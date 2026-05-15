<?php

namespace App\Filament\Resources\Assessments\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class AssessmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'email')
                    ->required(),
                Select::make('reviewer_id')
                    ->relationship('reviewer', 'nama_lengkap')
                    ->searchable(),
                Select::make('status')
                    ->options([
                        'ACTIVE' => 'Active',
                        'IN_PROGRESS' => 'In Progress',
                        'SUBMITTED' => 'Submitted',
                        'GRADED' => 'Graded',
                        'PUBLISHED' => 'Published',
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
