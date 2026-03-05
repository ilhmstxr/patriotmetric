<?php

namespace App\Filament\Resources\Pertanyaans\Schemas;

use Filament\Schemas\Schema;

class PertanyaanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('category_id')
                    ->label('Kategori')
                    ->relationship('kategori', 'nama_kategori')
                    ->required(),
                \Filament\Forms\Components\TextInput::make('kode_pertanyaan')
                    ->label('Kode/Indikator (Cth: PU1)'),
                \Filament\Forms\Components\Textarea::make('teks_pertanyaan')
                    ->label('Teks Pertanyaan')
                    ->required()
                    ->columnSpanFull(),
                \Filament\Forms\Components\Textarea::make('deskripsi')
                    ->label('Deskripsi Penjelasan')
                    ->columnSpanFull(),
                \Filament\Forms\Components\RichEditor::make('kebutuhan_bukti')
                    ->label('Kebutuhan Bukti (Evidence)')
                    ->columnSpanFull(),
                \Filament\Forms\Components\Select::make('tipe')
                    ->label('Tipe Jawaban')
                    ->options([
                        'pilihan_ganda' => 'Pilihan Ganda',
                        'teks_singkat' => 'Teks Singkat',
                    ])
                    ->live()
                    ->required(),
                \Filament\Forms\Components\TextInput::make('skor_maksimal')
                    ->label('Skor Maksimal')
                    ->numeric()
                    ->default(0)
                    ->required(),
                \Filament\Forms\Components\Repeater::make('opsi_jawaban')
                    ->label('Opsi Pilihan')
                    ->schema([
                        \Filament\Forms\Components\TextInput::make('teks')
                            ->label('Teks Opsi')
                            ->placeholder('Cth: Sangat Baik')
                            ->required(),
                    ])
                    // Hanya muncul jika tipe yang dipilih adalah 'pilihan_ganda'
                    ->visible(fn ($get): bool => $get('tipe') === 'pilihan_ganda')
                    ->columnSpanFull(),
            ]);
    }
}
