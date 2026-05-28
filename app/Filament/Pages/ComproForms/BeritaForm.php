<?php

namespace App\Filament\Pages\ComproForms;

use App\Filament\Pages\ComproForms\Concerns\WebpFileUpload;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;

class BeritaForm
{
    use WebpFileUpload;

    public static function schema(): array
    {
        return [
            Section::make('Hero')
                ->schema([
                    TextInput::make('hero.judul')->label('Judul')->maxLength(255)->required(),
                    Textarea::make('hero.deskripsi')->label('Deskripsi')->maxLength(500)->required(),
                ]),

            Section::make('Berita')
                ->schema([
                    Repeater::make('berita.daftar')
                        ->label('Daftar Berita')
                        ->schema([
                            DatePicker::make('tanggal')->label('Tanggal')->required(),
                            TextInput::make('judul')->label('Judul Berita')->maxLength(300)->required(),
                            Textarea::make('excerpt')->label('Ringkasan/Excerpt')->maxLength(500)->required(),
                            Textarea::make('konten')->label('Konten Lengkap')->rows(10)->required(),
                            self::makeImageUpload('gambar')
                                ->label('Gambar Thumbnail'),
                        ])
                        ->maxItems(50)
                        ->reorderable()
                        ->collapsible()
                        ->itemLabel(fn(array $state) => ($state['tanggal'] ?? '') . ' - ' . ($state['judul'] ?? 'Berita Baru')),
                ]),
        ];
    }
}
