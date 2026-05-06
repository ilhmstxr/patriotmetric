<?php

namespace App\Filament\Resources\Rubriks;

use App\Filament\Resources\Rubriks\Pages\CreateRubrik;
use App\Filament\Resources\Rubriks\Pages\EditRubrik;
use App\Filament\Resources\Rubriks\Pages\ListRubriks;
use App\Models\Kategori;
use BackedEnum;
use Filament\Actions\EditAction;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;

class RubrikResource extends Resource
{
    protected static ?string $model = Kategori::class;

    protected static ?string $navigationLabel = 'Rubrik Penilaian';

    protected static ?string $pluralLabel = 'Rubrik Penilaian';

    protected static ?string $singularLabel = 'Rubrik';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama_kategori')
                    ->label('Nama Kategori Rubrik')
                    ->required(),
                TextInput::make('bobot')
                    ->numeric()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_kategori')
                    ->label('Kategori')
                    ->searchable(),
                TextColumn::make('bobot')
                    ->label('Bobot (%)')
                    ->sortable(),
                TextColumn::make('pertanyaans_count')
                    ->label('Jumlah Pertanyaan')
                    ->counts('pertanyaans'),
            ])
            ->actions([
                EditAction::make()->label('Kelola Pertanyaan'),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            \App\Filament\Resources\Categories\RelationManagers\PertanyaansRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRubriks::route('/'),
            'create' => CreateRubrik::route('/create'),
            'edit' => EditRubrik::route('/{record}/edit'),
        ];
    }
}
