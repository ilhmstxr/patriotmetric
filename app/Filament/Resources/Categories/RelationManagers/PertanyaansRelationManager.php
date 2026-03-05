<?php

namespace App\Filament\Resources\Categories\RelationManagers;

use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PertanyaansRelationManager extends RelationManager
{
    protected static string $relationship = 'pertanyaans';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Textarea::make('teks_pertanyaan')
                    ->required()
                    ->columnSpanFull(),
                \Filament\Forms\Components\Select::make('tipe')
                    ->options([
                        'pilihan_ganda' => 'Pilihan Ganda',
                        'teks_singkat' => 'Teks Singkat',
                    ])
                    ->live()
                    ->required(),
                \Filament\Forms\Components\Repeater::make('opsi_jawaban')
                    ->schema([
                        \Filament\Forms\Components\TextInput::make('teks')
                            ->label('Teks Opsi (Cth: Sangat Baik)')
                            ->required(),
                        \Filament\Forms\Components\TextInput::make('nilai')
                            ->label('Bobot Nilai (Cth: 5)')
                            ->numeric()
                            ->required(),
                    ])
                    ->visible(fn (\Filament\Forms\Get $get): bool => $get('tipe') === 'pilihan_ganda')
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('teks_pertanyaan')
            ->columns([
                TextColumn::make('teks_pertanyaan')
                    ->label('Pertanyaan')
                    ->limit(60)
                    ->searchable(),
                TextColumn::make('tipe')
                    ->label('Tipe Jawaban')
                    ->badge(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
                AssociateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DissociateAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DissociateBulkAction::make(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
