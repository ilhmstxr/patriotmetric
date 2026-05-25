<?php

namespace App\Filament\Resources\Pertanyaans\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PertanyaanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('category_id')
                    ->label('Kategori')
                    ->relationship('kategori', 'nama_kategori')
                    ->required(),
                TextInput::make('kode_pertanyaan')
                    ->label('Kode/Indikator (Cth: PU1)'),
                Textarea::make('teks_pertanyaan')
                    ->label('Teks Pertanyaan')
                    ->required()
                    ->columnSpanFull(),
                RichEditor::make('kebutuhan_bukti')
                    ->label('Kebutuhan Bukti (Evidence)')
                    ->columnSpanFull(),
                Select::make('tipe')
                    ->label('Tipe Jawaban')
                    ->options([
                        'pilihan_ganda' => 'Pilihan Ganda',
                        'isian_singkat' => 'Isian Singkat',
                        'otomatis_sistem' => 'Otomatis Sistem',
                    ])
                    ->live()
                    ->required(),
                /* TextInput::make('skor_maksimal')
                    ->label('Skor Maksimal')
                    ->numeric()
                    ->default(0)
                    ->required(), */
                Repeater::make('OpsiJawaban')
                    ->relationship()
                    ->label('Opsi Pilihan')
                    ->schema([
                        Select::make('opsi_jawaban')
                            ->label('Label')
                            ->options(['0'=>'0','1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5'])
                            ->required(),
                        TextInput::make('value')
                            ->label('Skor')
                            ->numeric()
                            ->required(),
                        TextInput::make('keterangan')
                            ->label('Keterangan')
                            ->required(),
                    ])
                    ->columns(3)
                    // Hanya muncul jika tipe yang dipilih adalah 'pilihan_ganda'
                    ->visible(fn ($get): bool => $get('tipe') === 'pilihan_ganda')
                    ->columnSpanFull(),
            ]);
    }
}
