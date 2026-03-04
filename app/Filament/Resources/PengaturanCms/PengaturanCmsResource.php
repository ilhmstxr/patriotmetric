<?php

namespace App\Filament\Resources\PengaturanCms;

use App\Filament\Resources\PengaturanCms\Pages\CreatePengaturanCms;
use App\Filament\Resources\PengaturanCms\Pages\EditPengaturanCms;
use App\Filament\Resources\PengaturanCms\Pages\ListPengaturanCms;
use App\Filament\Resources\PengaturanCms\Schemas\PengaturanCmsForm;
use App\Filament\Resources\PengaturanCms\Tables\PengaturanCmsTable;
use App\Models\PengaturanCms;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PengaturanCmsResource extends Resource
{
    protected static ?string $model = PengaturanCms::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return PengaturanCmsForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PengaturanCmsTable::configure($table);
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
            'index' => ListPengaturanCms::route('/'),
            'create' => CreatePengaturanCms::route('/create'),
            'edit' => EditPengaturanCms::route('/{record}/edit'),
        ];
    }
}
