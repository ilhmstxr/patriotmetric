<?php

namespace App\Filament\Resources\Assessments\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\SelectColumn;
use Illuminate\Database\Eloquent\Collection;

class AssessmentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('index')
                    ->label('No')
                    ->rowIndex(),
                TextColumn::make('tahun_periode')
                    ->label('Periode')
                    ->sortable(),
                TextColumn::make('institusi.nama_institusi')
                    ->label('Nama Instansi')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('nama_pic')
                    ->label('Nama PIC')
                    ->searchable()
                    ->sortable(),
                SelectColumn::make('reviewer_id')
                    ->label('Reviewer')
                    ->options(\App\Models\Reviewer::pluck('nama_lengkap', 'id'))
                    ->placeholder('Pilih Reviewer')
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'UNVERIFIED'  => 'danger',
                        'ACTIVE'      => 'gray',
                        'IN_PROGRESS' => 'info',
                        'SUBMITTED'   => 'warning',
                        'GRADED'      => 'success',
                        'PUBLISHED'   => 'success',
                        default       => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Action::make('view')
                    ->label('View')
                    ->icon('heroicon-o-eye')
                    ->url(fn ($record) => '/admin/assessment-detail/' . $record->id)
                    ->openUrlInNewTab(),
                EditAction::make(),
            ])
            ->recordActions([
                Action::make('view')
                    ->label('View')
                    ->icon('heroicon-o-eye')
                    ->url(fn ($record) => '/admin/assessment-detail/' . $record->id)
                    ->openUrlInNewTab(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    // Publish semua yang GRADED sekaligus
                    BulkAction::make('publishAll')
                        ->label('Publish Nilai Terpilih')
                        ->icon('heroicon-o-eye')
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading('Publish Nilai Semua Peserta Terpilih?')
                        ->modalDescription('Hanya peserta dengan status GRADED yang akan dipublikasikan.')
                        ->action(function (Collection $records): void {
                            $records->where('status', 'GRADED')
                                ->each(fn ($record) => $record->update(['status' => 'PUBLISHED']));
                        }),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
