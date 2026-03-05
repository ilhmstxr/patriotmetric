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
                \Filament\Tables\Columns\TextColumn::make('kode_pertanyaan')
                    ->label('Kode')
                    ->searchable()
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('kategori.nama_kategori')
                    ->label('Kategori')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                \Filament\Tables\Columns\TextColumn::make('teks_pertanyaan')
                    ->label('Pertanyaan')
                    ->limit(60)
                    ->searchable(),
                \Filament\Tables\Columns\TextColumn::make('tipe')
                    ->label('Tipe Jawaban')
                    ->badge(),
                \Filament\Tables\Columns\TextColumn::make('skor_maksimal')
                    ->label('Skor Maksimal')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->groups([
                \Filament\Tables\Grouping\Group::make('kategori.nama_kategori')
                    ->label('Kategori')
                    ->collapsible()
                    ->titlePrefixedWithLabel(false),
            ])
            ->defaultGroup('kategori.nama_kategori')
            ->collapsedGroupsByDefault(true)
            ->recordActions([
                EditAction::make()->label('Ubah'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->label('Hapus Terpilih'),
                ])->label('Aksi Massal'),
            ]);
    }
}
