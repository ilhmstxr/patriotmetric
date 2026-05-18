<?php

namespace App\Filament\Resources\PengaturanCms\Tables;

use App\Filament\Resources\PengaturanCms\PengaturanCmsResource;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;

class PengaturanCmsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('key')
                    ->searchable()
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('value')
                    ->limit(50)
                    ->html(),
                \Filament\Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordUrl(fn ($record) => PengaturanCmsResource::getUrl('edit', ['record' => $record]))
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
