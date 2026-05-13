<?php

namespace App\Filament\Resources\Pertanyaans\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Filament\Actions\Action;

class PertanyaansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode_pertanyaan')
                    ->label('Kode')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('kategori.nama_kategori')
                    ->label('Kategori')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('teks_pertanyaan')
                    ->label('Pertanyaan')
                    ->searchable()
                    ->wrap(),
                TextColumn::make('tipe')
                    ->label('Tipe Jawaban')
                    ->badge(),
                /* TextColumn::make('skor_maksimal')
                    ->label('Skor Maksimal')
                    ->sortable(), */
            ])
            ->filters([
                //
            ])
            ->groups([
                Group::make('kategori.nama_kategori')
                    ->label('Kategori')
                    ->collapsible()
                    ->titlePrefixedWithLabel(false),
            ])
            ->defaultGroup('kategori.nama_kategori')
            ->collapsedGroupsByDefault(false)
            ->recordActions([
                Action::make('manageOptions')
                    ->label('Kelola Opsi')
                    ->icon('heroicon-o-list-bullet')
                    ->color('info')
                    ->url(fn ($record) => \App\Filament\Resources\Pertanyaans\PertanyaanResource::getUrl('edit', ['record' => $record])),
            ])
            ->defaultPaginationPageOption(5)
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ])->label('Aksi Massal'),
            ]);
    }
}
