<?php

namespace App\Filament\Resources\Pertanyaans\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class OpsiJawabanRelationManager extends RelationManager
{
    protected static string $relationship = 'OpsiJawaban';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('opsi_jawaban')
                    ->label('Label Opsi')
                    ->options([
                        '0' => '0',
                        '1' => '1',
                        '2' => '2',
                        '3' => '3',
                        '4' => '4',
                        '5' => '5',
                    ])
                    ->required(),
                TextInput::make('value')
                    ->label('Nilai/Skor')
                    ->numeric()
                    ->required(),
                Textarea::make('keterangan')
                    ->label('Teks Opsi / Keterangan')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('opsi_jawaban')
                    ->label('Label')
                    ->sortable(),
                TextColumn::make('value')
                    ->label('Skor')
                    ->sortable(),
                TextColumn::make('keterangan')
                    ->label('Keterangan')
                    ->wrap(),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
