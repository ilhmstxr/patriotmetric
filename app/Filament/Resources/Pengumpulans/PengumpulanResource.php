<?php

namespace App\Filament\Resources\Pengumpulans;

use App\Filament\Resources\Pengumpulans\Pages\CreatePengumpulan;
use App\Filament\Resources\Pengumpulans\Pages\EditPengumpulan;
use App\Filament\Resources\Pengumpulans\Pages\ListPengumpulans;
use App\Filament\Resources\Pengumpulans\Schemas\PengumpulanForm;
use App\Filament\Resources\Pengumpulans\Tables\PengumpulansTable;
use App\Models\Pengumpulan;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\RepeatableEntry;

class PengumpulanResource extends Resource
{
    protected static ?string $model = Pengumpulan::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return PengumpulanForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PengumpulansTable::configure($table);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('user.name')->label('Nama Submitter'),
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
            'index' => ListPengumpulans::route('/'),
            'create' => CreatePengumpulan::route('/create'),
            'view' => \App\Filament\Resources\Pengumpulans\Pages\ViewPengumpulan::route('/{record}'),
            'edit' => EditPengumpulan::route('/{record}/edit'),
        ];
    }
}
