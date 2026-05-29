<?php

namespace App\Filament\Pages\ComproForms;

use App\Filament\Pages\ComproForms\Concerns\WebpFileUpload;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;

class PengumumanForm
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

            Section::make('Dokumen')
                ->schema([
                    Repeater::make('artikel.daftar')
                        ->label('Daftar Dokumen')
                        ->schema([
                            DatePicker::make('tanggal')->label('Tanggal')->required(),
                            TextInput::make('judul')->label('Judul Dokumen')->maxLength(200)->required(),
                            Textarea::make('excerpt')->label('Keterangan (opsional)')->maxLength(500),
                            FileUpload::make('dokumen')
                                ->label('File Dokumen (PDF)')
                                ->disk('cms')
                                ->directory('documents')
                                ->visibility('public')
                                ->acceptedFileTypes(['application/pdf'])
                                ->maxSize(10240),
                        ])
                        ->maxItems(50)
                        ->reorderable()
                        ->collapsible()
                        ->itemLabel(fn(array $state) => ($state['tanggal'] ?? '') . ' - ' . ($state['judul'] ?? 'Dokumen Baru')),
                ]),
        ];
    }
}
