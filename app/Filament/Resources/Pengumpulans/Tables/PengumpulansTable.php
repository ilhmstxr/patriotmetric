<?php

namespace App\Filament\Resources\Pengumpulans\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;

class PengumpulansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('user.name')
                    ->label('Peserta')
                    ->searchable()
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('total_skor_sistem')
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('total_skor_akhir')
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('reviewer.name')
                    ->label('Reviewer')
                    ->searchable()
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                Action::make('assignReviewer')
                    ->label('Tugaskan Reviewer')
                    ->icon('heroicon-o-user-plus')
                    ->form([
                        \Filament\Forms\Components\Select::make('reviewer_id')
                            ->label('Pilih Reviewer')
                            ->options(\App\Models\User::where('role', 'reviewer')->pluck('name', 'id'))
                            ->default(fn(\Illuminate\Database\Eloquent\Model $record) => $record->reviewer_id)
                            ->required(),
                    ])
                    ->action(function (\Illuminate\Database\Eloquent\Model $record, array $data) {
                        app(\App\Services\pengumpulanService::class)->assignReviewer($record->id, $data['reviewer_id']);
                    })
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
