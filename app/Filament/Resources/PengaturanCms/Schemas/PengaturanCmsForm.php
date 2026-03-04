<?php

namespace App\Filament\Resources\PengaturanCms\Schemas;

use Filament\Schemas\Schema;

class PengaturanCmsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\TextInput::make('key')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                \Filament\Forms\Components\RichEditor::make('value')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }
}
