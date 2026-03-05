<?php

namespace App\Filament\Resources\Pertanyaans;

use App\Filament\Resources\Pertanyaans\Pages\CreatePertanyaan;
use App\Filament\Resources\Pertanyaans\Pages\EditPertanyaan;
use App\Filament\Resources\Pertanyaans\Pages\ListPertanyaans;
use App\Filament\Resources\Pertanyaans\Schemas\PertanyaanForm;
use App\Filament\Resources\Pertanyaans\Tables\PertanyaansTable;
use App\Models\pertanyaan;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PertanyaanResource extends Resource
{
    protected static ?string $model = pertanyaan::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'teks_pertanyaan';

    public static function form(Schema $schema): Schema
    {
        return PertanyaanForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PertanyaansTable::configure($table);
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
            'index' => ListPertanyaans::route('/'),
            'create' => CreatePertanyaan::route('/create'),
            'edit' => EditPertanyaan::route('/{record}/edit'),
        ];
    }
}
