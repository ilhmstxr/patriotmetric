<?php

namespace App\Filament\Resources\Assessments;

use App\Filament\Resources\Assessments\Pages\CreateAssessment;
use App\Filament\Resources\Assessments\Pages\EditAssessment;
use App\Filament\Resources\Assessments\Pages\ListAssessments;
use App\Filament\Resources\Assessments\Schemas\AssessmentForm;
use App\Filament\Resources\Assessments\Tables\AssessmentsTable;
use App\Models\Assessment;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\RepeatableEntry;

class AssessmentResource extends Resource
{
    protected static ?string $model = Assessment::class;

    protected static bool $shouldRegisterNavigation = true;

    protected static ?string $navigationLabel = 'Monitoring Assessment';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return AssessmentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AssessmentsTable::configure($table);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('institusi.nama_institusi')->label('Nama Peserta'),
                TextEntry::make('status')->badge(),
                TextEntry::make('total_skor_akhir'),
                RepeatableEntry::make('jawabans')
                    ->schema([
                        TextEntry::make('pertanyaan.teks_pertanyaan'),
                        TextEntry::make('jawaban_teks'),
                        TextEntry::make('tautan_bukti_drive')->url(fn($state) => $state)->openUrlInNewTab(),
                        TextEntry::make('skor_sistem'),
                        TextEntry::make('skor_validasi_reviewer'),
                    ])
                    ->columns(2)
                    ->columnSpanFull()
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAssessments::route('/'),
            'create' => CreateAssessment::route('/create'),
            'view' => \App\Filament\Resources\Assessments\Pages\ViewAssessment::route('/{record}'),
            'edit' => EditAssessment::route('/{record}/edit'),
        ];
    }
}
