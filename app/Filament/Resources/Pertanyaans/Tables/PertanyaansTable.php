<?php

namespace App\Filament\Resources\Pertanyaans\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;

class PertanyaansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('kategori.nama_kategori')
                    ->label('Kategori')
                    ->searchable()
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('teks_pertanyaan')
                    ->label('Pertanyaan')
                    ->limit(60)
                    ->searchable(),
                \Filament\Tables\Columns\TextColumn::make('tipe')
                    ->label('Tipe Jawaban')
                    ->badge(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
