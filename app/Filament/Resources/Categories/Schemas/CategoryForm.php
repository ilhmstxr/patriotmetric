<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Schemas\Schema;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\TextInput::make('nama_kategori')
                    ->label('Nama Kategori')
                    ->required()
                    ->maxLength(255),
                \Filament\Forms\Components\Textarea::make('deskripsi')
                    ->label('Deskripsi')
                    ->nullable()
                    ->columnSpanFull(),
            ]);
    }
}
