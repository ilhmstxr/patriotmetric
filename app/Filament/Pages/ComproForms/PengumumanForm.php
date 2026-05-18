<?php

namespace App\Filament\Pages\ComproForms;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;

class PengumumanForm
{
    public static function schema(): array
    {
        return [
            Section::make('Hero')
                ->schema([
                    TextInput::make('hero.judul')->label('Judul')->maxLength(255)->required(),
                    Textarea::make('hero.deskripsi')->label('Deskripsi')->maxLength(500)->required(),
                ]),

            Section::make('Artikel')
                ->schema([
                    Repeater::make('artikel.daftar')
                        ->label('Daftar Artikel')
                        ->schema([
                            DatePicker::make('tanggal')->label('Tanggal')->required(),
                            TextInput::make('judul')->label('Judul Artikel')->maxLength(200)->required(),
                            Textarea::make('excerpt')->label('Ringkasan/Excerpt')->maxLength(500)->required(),
                            FileUpload::make('gambar')
                                ->label('Gambar Thumbnail')
                                ->image()
                                ->disk('cms')
                                ->directory('images')
                                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                ->maxSize(5120),
                        ])
                        ->maxItems(50)
                        ->reorderable()
                        ->collapsible()
                        ->itemLabel(fn(array $state) => ($state['tanggal'] ?? '') . ' - ' . ($state['judul'] ?? 'Artikel Baru')),
                ]),
        ];
    }
}
