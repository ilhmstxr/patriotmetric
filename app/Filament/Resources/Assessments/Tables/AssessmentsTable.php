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
use Illuminate\Database\Eloquent\Collection;

class AssessmentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('institusi.nama_institusi')
                    ->label('Peserta')
                    ->searchable()
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
                TextColumn::make('total_skor_sistem')
                    ->sortable(),
                TextColumn::make('total_skor_akhir')
                    ->sortable(),
                TextColumn::make('reviewer.nama_lengkap')
                    ->label('Reviewer')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('created_at')
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
                        Select::make('reviewer_id')
                            ->label('Pilih Reviewer')
                            ->options(\App\Models\Reviewer::pluck('nama_lengkap', 'id'))
                            ->searchable()
                            ->default(fn(\Illuminate\Database\Eloquent\Model $record) => $record->reviewer_id)
                            ->required(),
                    ])
                    ->action(function (\Illuminate\Database\Eloquent\Model $record, array $data) {
                        app(\App\Services\AssessmentService::class)->assignReviewer($record->id, $data['reviewer_id']);
                    })
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
