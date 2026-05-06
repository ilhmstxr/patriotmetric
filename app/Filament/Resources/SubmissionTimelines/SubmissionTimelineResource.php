<?php

namespace App\Filament\Resources\SubmissionTimelines;

use App\Filament\Resources\SubmissionTimelines\Pages\CreateSubmissionTimeline;
use App\Filament\Resources\SubmissionTimelines\Pages\EditSubmissionTimeline;
use App\Filament\Resources\SubmissionTimelines\Pages\ListSubmissionTimelines;
use App\Filament\Resources\SubmissionTimelines\Schemas\SubmissionTimelineForm;
use App\Filament\Resources\SubmissionTimelines\Tables\SubmissionTimelinesTable;
use App\Models\SubmissionTimeline;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SubmissionTimelineResource extends Resource
{
    protected static ?string $model = SubmissionTimeline::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClock;

    protected static ?string $navigationLabel = 'Timeline Submission';

    protected static ?string $modelLabel = 'Timeline Submission';

    protected static ?string $pluralModelLabel = 'Timeline Submission';

    protected static ?int $navigationSort = 30;

    public static function form(Schema $schema): Schema
    {
        return SubmissionTimelineForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SubmissionTimelinesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSubmissionTimelines::route('/'),
            'create' => CreateSubmissionTimeline::route('/create'),
            'edit' => EditSubmissionTimeline::route('/{record}/edit'),
        ];
    }
}
