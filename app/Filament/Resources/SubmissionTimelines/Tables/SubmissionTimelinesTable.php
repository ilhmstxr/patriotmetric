<?php

namespace App\Filament\Resources\SubmissionTimelines\Tables;

use App\Models\SubmissionTimeline;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Carbon;

class SubmissionTimelinesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('tahun_periode', 'desc')
            ->columns([
                TextColumn::make('tahun_periode')
                    ->label('Periode')
                    ->sortable(),
                TextColumn::make('opens_at')
                    ->label('Dibuka')
                    ->dateTime('d M Y H:i')
                    ->placeholder('— sejak awal —')
                    ->sortable(),
                TextColumn::make('closes_at')
                    ->label('Ditutup')
                    ->dateTime('d M Y H:i')
                    ->placeholder('— tanpa deadline —')
                    ->sortable(),
                IconColumn::make('is_locked')
                    ->label('Force Lock')
                    ->boolean(),
                TextColumn::make('current_state')
                    ->label('Status Saat Ini')
                    ->state(fn (SubmissionTimeline $record): string => static::stateLabel($record))
                    ->badge()
                    ->color(fn (SubmissionTimeline $record): string => static::stateColor($record)),
                TextColumn::make('note')
                    ->label('Catatan')
                    ->limit(40)
                    ->toggleable(),
                TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordActions([
                EditAction::make()->label('Ubah'),
                DeleteAction::make()->label('Hapus'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    protected static function stateLabel(SubmissionTimeline $record): string
    {
        $now = Carbon::now();
        if ($record->is_locked) {
            return 'Dikunci Admin';
        }
        if ($record->opens_at && $now->lt($record->opens_at)) {
            return 'Belum Dibuka';
        }
        if ($record->closes_at && $now->gt($record->closes_at)) {
            return 'Sudah Ditutup';
        }
        return 'Terbuka';
    }

    protected static function stateColor(SubmissionTimeline $record): string
    {
        return match (static::stateLabel($record)) {
            'Terbuka' => 'success',
            'Belum Dibuka' => 'warning',
            'Sudah Ditutup', 'Dikunci Admin' => 'danger',
            default => 'gray',
        };
    }
}
