<?php

namespace App\Filament\Pages\ComproForms;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

class PanduanForm
{
    public static function schema(): array
    {
        return [
            Section::make('Hero')
                ->schema([
                    TextInput::make('hero.judul')->label('Judul')->maxLength(255)->required(),
                    Textarea::make('hero.deskripsi')->label('Deskripsi')->maxLength(500)->required(),
                    TextInput::make('hero.tombol_teks')->label('Teks Tombol Pedoman')->maxLength(100)->required(),
                    TextInput::make('hero.tombol_link')->label('Link Pedoman')->url()->maxLength(255)->required(),
                ]),

            Section::make('Steps')
                ->schema([
                    Repeater::make('steps.daftar')
                        ->label('Daftar Langkah')
                        ->schema([
                            TextInput::make('label')->label('Step Label')->maxLength(50)->required(),
                            TextInput::make('judul')->label('Judul')->maxLength(150)->required(),
                            Textarea::make('deskripsi')->label('Deskripsi')->maxLength(500)->required(),
                            TextInput::make('icon')->label('Icon (Lucide icon name)')->maxLength(50)->required(),
                        ])
                        ->maxItems(50)
                        ->reorderable()
                        ->collapsible()
                        ->itemLabel(fn(array $state) => ($state['label'] ?? '') . ' - ' . ($state['judul'] ?? 'Item Baru')),
                ]),

            Section::make('FAQ')
                ->schema([
                    TextInput::make('faq.judul')->label('Judul Section')->maxLength(255)->required(),
                    Repeater::make('faq.daftar')
                        ->label('Daftar FAQ')
                        ->schema([
                            TextInput::make('pertanyaan')->label('Pertanyaan')->maxLength(255)->required(),
                            Textarea::make('jawaban')->label('Jawaban')->maxLength(1000)->required(),
                        ])
                        ->maxItems(50)
                        ->reorderable()
                        ->collapsible()
                        ->itemLabel(fn(array $state) => $state['pertanyaan'] ?? 'FAQ Baru'),
                ]),
        ];
    }
}
