<?php

namespace App\Filament\Pages\ComproForms;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;

class PenghargaanForm
{
    public static function schema(): array
    {
        return [
            Section::make('Hero')
                ->schema([
                    TextInput::make('hero.judul')->label('Judul')->maxLength(255)->required(),
                    Textarea::make('hero.deskripsi')->label('Deskripsi')->maxLength(500)->required(),
                    FileUpload::make('hero.background_image')
                        ->label('Background Image')
                        ->image()
                        ->disk('cms')
                        ->directory('images')
                        ->visibility('public')
                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                        ->maxSize(5120),
                ]),

            Section::make('Daftar Penerima')
                ->schema([
                    TextInput::make('daftar-penerima.judul')->label('Judul Section')->maxLength(255)->required(),
                    Repeater::make('daftar-penerima.daftar')
                        ->label('Daftar Institusi Penerima')
                        ->schema([
                            TextInput::make('nama')->label('Nama Institusi')->maxLength(150)->required(),
                            FileUpload::make('logo')
                                ->label('Logo Institusi')
                                ->image()
                                ->disk('cms')
                                ->directory('images')
                                ->visibility('public')
                                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                ->maxSize(5120)
                                ->required(),
                            TextInput::make('rating')
                                ->label('Rating Bintang')
                                ->numeric()
                                ->minValue(1)
                                ->maxValue(5)
                                ->step(0.5)
                                ->required(),
                        ])
                        ->maxItems(50)
                        ->reorderable()
                        ->collapsible()
                        ->itemLabel(fn(array $state) => ($state['nama'] ?? 'Institusi Baru') . ' - ⭐' . ($state['rating'] ?? '')),
                ]),
        ];
    }
}
