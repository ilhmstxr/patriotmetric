<?php

namespace App\Filament\Resources\Reviewers;

use App\Filament\Resources\Reviewers\Pages\CreateReviewer;
use App\Filament\Resources\Reviewers\Pages\EditReviewer;
use App\Filament\Resources\Reviewers\Pages\ListReviewers;
use App\Filament\Resources\Reviewers\Schemas\ReviewerForm;
use App\Filament\Resources\Reviewers\Tables\ReviewersTable;
use App\Models\Reviewer;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ReviewerResource extends Resource
{
    protected static ?string $model = \App\Models\Pengumpulan::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static ?string $navigationLabel = 'Reviewer';

    protected static ?string $modelLabel = 'Reviewer';

    protected static ?string $pluralModelLabel = 'Reviewer';

    protected static ?int $navigationSort = 20;

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
