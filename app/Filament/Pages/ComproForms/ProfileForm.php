<?php

namespace App\Filament\Pages\ComproForms;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;

class ProfileForm
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
                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                        ->maxSize(5120),
                ]),

            Section::make('Latar Belakang')
                ->schema([
                    TextInput::make('latar-belakang.judul')->label('Judul Section')->maxLength(255)->required(),
                    RichEditor::make('latar-belakang.deskripsi')
                        ->label('Deskripsi (semua paragraf)')
                        ->maxLength(10000)
                        ->required()
                        ->toolbarButtons([
                            'bold', 'italic', 'underline',
                            'bulletList', 'orderedList',
                            'link', 'undo', 'redo',
                        ]),
                ]),

            Section::make('Tujuan Utama')
                ->schema([
                    TextInput::make('tujuan-utama.judul')->label('Judul Section')->maxLength(255)->required(),
                    Textarea::make('tujuan-utama.deskripsi')->label('Deskripsi Section')->maxLength(500),
                    Repeater::make('tujuan-utama.daftar')
                        ->label('Daftar Tujuan')
                        ->schema([
                            TextInput::make('nomor')->label('Nomor')->maxLength(10)->required(),
                            TextInput::make('judul')->label('Judul')->maxLength(150)->required(),
                            Textarea::make('deskripsi')->label('Deskripsi')->maxLength(500)->required(),
                        ])
                        ->maxItems(50)
                        ->reorderable()
                        ->collapsible()
                        ->itemLabel(fn(array $state) => ($state['nomor'] ?? '') . ' - ' . ($state['judul'] ?? 'Item Baru')),
                ]),
        ];
    }
}
