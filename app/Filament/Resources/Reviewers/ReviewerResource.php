<?php

namespace App\Filament\Resources\Reviewers;

use App\Filament\Resources\Reviewers\Pages\ListReviewers;
use App\Filament\Resources\Reviewers\Tables\ReviewersTable;
use App\Models\Assessment;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ReviewerResource extends Resource
{
    protected static ?string $model = Assessment::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static ?string $navigationLabel = 'Plotting Reviewer';

    protected static ?string $modelLabel = 'Plotting Reviewer';

    protected static ?string $pluralModelLabel = 'Plotting Reviewer';

    protected static ?int $navigationSort = 20;

    public static function canAccess(): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        return ReviewersTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListReviewers::route('/'),
        ];
    }
}
