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
                    ->relationship('kategori', 'nama_kategori')
                    ->required(),
                \Filament\Forms\Components\Textarea::make('teks_pertanyaan')
                    ->required()
                    ->columnSpanFull(),
                \Filament\Forms\Components\Select::make('tipe')
                    ->options([
                        'pilihan_ganda' => 'Pilihan Ganda',
                        'teks_singkat' => 'Teks Singkat',
                    ])
                    ->live()
                    ->required(),
                \Filament\Forms\Components\Repeater::make('opsi_jawaban')
                    ->schema([
                        \Filament\Forms\Components\TextInput::make('teks')
                            ->label('Teks Opsi (Cth: Sangat Baik)')
                            ->required(),
                        \Filament\Forms\Components\TextInput::make('nilai')
                            ->label('Bobot Nilai (Cth: 5)')
                            ->numeric()
                            ->required(),
                    ])
                    // Hanya muncul jika tipe yang dipilih adalah 'pilihan_ganda'
                    ->visible(fn (\Filament\Forms\Get $get): bool => $get('tipe') === 'pilihan_ganda')
                    ->columnSpanFull(),
            ]);
    }
}
