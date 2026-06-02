<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BeritaResource\Pages;
use App\Models\Berita;
use BackedEnum;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BeritaResource extends Resource
{
    protected static ?string $model = Berita::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedNewspaper;

    protected static \UnitEnum|string|null $navigationGroup = 'CMS Compro';

    protected static ?string $navigationLabel = 'Berita';

    protected static ?int $navigationSort = 9;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('judul')
                ->label('Judul')
                ->required()
                ->maxLength(300),
            TextInput::make('slug')
                ->label('Slug')
                ->maxLength(350)
                ->unique(ignoreRecord: true)
                ->helperText('Otomatis dibuat dari judul jika dikosongkan'),
            Textarea::make('excerpt')
                ->label('Ringkasan')
                ->maxLength(500)
                ->helperText('Otomatis dibuat dari konten jika dikosongkan'),
            RichEditor::make('konten')
                ->label('Konten Lengkap')
                ->required()
                ->columnSpanFull()
                ->fileAttachmentsDisk('cms')
                ->fileAttachmentsDirectory('berita')
                ->fileAttachmentsVisibility('public'),
            FileUpload::make('gambar')
                ->label('Gambar Utama')
                ->image()
                ->disk('cms')
                ->directory(fn ($record) => 'berita/' . ($record?->id ?? 'temp'))
                ->maxSize(2048),
            DatePicker::make('tanggal')
                ->label('Tanggal')
                ->required()
                ->default(now()),
            Toggle::make('is_published')
                ->label('Publikasikan')
                ->default(false),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('gambar')->disk('cms')->label('Gambar')->width(80)->height(50),
                TextColumn::make('judul')->label('Judul')->searchable()->limit(50),
                TextColumn::make('tanggal')->label('Tanggal')->date('j M Y')->sortable(),
                IconColumn::make('is_published')->label('Published')->boolean(),
                TextColumn::make('updated_at')->label('Diperbarui')->since()->sortable(),
            ])
            ->defaultSort('tanggal', 'desc')
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBeritas::route('/'),
            'create' => Pages\CreateBerita::route('/create'),
            'edit' => Pages\EditBerita::route('/{record}/edit'),
        ];
    }
}
