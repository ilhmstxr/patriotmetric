<?php

namespace App\Filament\Pages\ComproForms;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;

class TimForm
{
    public static function schema(): array
    {
        return [
            Section::make('Hero')
                ->schema([
                    TextInput::make('hero.judul')->label('Judul')->maxLength(255)->required(),
                    Textarea::make('hero.deskripsi')->label('Deskripsi')->maxLength(500)->required(),
                ]),

            Section::make('Team Grid')
                ->schema([
                    Repeater::make('team-grid.daftar')
                        ->label('Daftar Anggota Tim')
                        ->schema([
                            TextInput::make('nama')->label('Nama')->maxLength(100)->required(),
                            TextInput::make('role')->label('Role/Jabatan')->maxLength(100)->required(),
                            FileUpload::make('foto')
                                ->label('Foto')
                                ->image()
                                ->disk('cms')
                                ->directory('images')
                                ->visibility('public')
                                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                ->maxSize(5120),
                        ])
                        ->maxItems(50)
                        ->reorderable()
                        ->collapsible()
                        ->itemLabel(fn(array $state) => $state['nama'] ?? 'Anggota Baru'),
                ]),
        ];
    }
}
