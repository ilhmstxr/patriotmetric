<?php

namespace App\Filament\Pages\ComproForms;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;

class WelcomeForm
{
    public static function schema(): array
    {
        return [
            Section::make('Hero')
                ->schema([
                    TextInput::make('hero.judul')->label('Judul Utama')->maxLength(255)->required(),
                    Textarea::make('hero.deskripsi')->label('Deskripsi')->maxLength(500)->required(),
                    FileUpload::make('hero.background_image')
                        ->label('Background Image')
                        ->image()
                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                        ->maxSize(2048),
                ]),

            Section::make('About')
                ->schema([
                    TextInput::make('about.judul')->label('Judul')->maxLength(255)->required(),
                    RichEditor::make('about.deskripsi')
                        ->label('Deskripsi (paragraf + bullet points)')
                        ->maxLength(10000)
                        ->required()
                        ->toolbarButtons([
                            'bold', 'italic', 'underline',
                            'bulletList', 'orderedList',
                            'link', 'undo', 'redo',
                        ]),
                    TextInput::make('about.video_url')->label('URL Video YouTube')->url()->maxLength(255),
                ]),

            Section::make('Institusi Partisipan')
                ->schema([
                    TextInput::make('institusi.judul')->label('Judul Section')->maxLength(255)->required(),
                    Textarea::make('institusi.deskripsi')->label('Deskripsi Section')->maxLength(500),
                    Repeater::make('institusi.daftar_baris_1')
                        ->label('Daftar Institusi Baris 1')
                        ->schema([
                            TextInput::make('nama')->label('Nama Institusi')->maxLength(150)->required(),
                            FileUpload::make('logo')
                                ->label('Logo Institusi')
                                ->image()
                                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                ->maxSize(2048)
                                ->required(),
                        ])
                        ->maxItems(50)
                        ->reorderable()
                        ->collapsible()
                        ->itemLabel(fn(array $state) => $state['nama'] ?? 'Institusi Baru'),
                    Repeater::make('institusi.daftar_baris_2')
                        ->label('Daftar Institusi Baris 2')
                        ->schema([
                            TextInput::make('nama')->label('Nama Institusi')->maxLength(150)->required(),
                            FileUpload::make('logo')
                                ->label('Logo Institusi')
                                ->image()
                                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                ->maxSize(2048)
                                ->required(),
                        ])
                        ->maxItems(50)
                        ->reorderable()
                        ->collapsible()
                        ->itemLabel(fn(array $state) => $state['nama'] ?? 'Institusi Baru'),
                ]),

            Section::make('Timeline')
                ->schema([
                    TextInput::make('timeline.judul')->label('Judul Section')->maxLength(255)->required(),
                    Textarea::make('timeline.deskripsi')->label('Deskripsi Section')->maxLength(500),
                    Repeater::make('timeline.daftar')
                        ->label('Daftar Timeline')
                        ->schema([
                            TextInput::make('nomor')->label('Nomor')->maxLength(10)->required(),
                            TextInput::make('tanggal')->label('Tanggal')->maxLength(50)->required(),
                            TextInput::make('judul')->label('Judul')->maxLength(150)->required(),
                            Textarea::make('deskripsi')->label('Deskripsi')->maxLength(500)->required(),
                        ])
                        ->maxItems(50)
                        ->reorderable()
                        ->collapsible()
                        ->itemLabel(fn(array $state) => ($state['nomor'] ?? '') . ' - ' . ($state['judul'] ?? 'Item Baru')),
                ]),

            Section::make('Instagram')
                ->schema([
                    TextInput::make('instagram.judul')->label('Judul Section')->maxLength(255),
                    Textarea::make('instagram.deskripsi')->label('Deskripsi')->maxLength(500),
                    Repeater::make('instagram.posts')
                        ->label('Post Instagram')
                        ->schema([
                            TextInput::make('url')->label('URL Post')->url()->maxLength(255)->required(),
                            FileUpload::make('gambar')->label('Gambar')->image()->maxSize(2048),
                            TextInput::make('alt_text')->label('Alt Text')->maxLength(255),
                        ])
                        ->maxItems(50)
                        ->reorderable()
                        ->collapsible(),
                ]),
        ];
    }
}
