<?php

namespace App\Filament\Resources\Categories\RelationManagers;

use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PertanyaansRelationManager extends RelationManager
{
    protected static string $relationship = 'pertanyaans';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
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
                    ->visible(fn($get): bool => $get('tipe') === 'pilihan_ganda')
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make()->label('Tambah Pertanyaan'),
                // AssociateAction::make()->label('Hubungkan Pertanyaan'),
            ])
            ->recordTitleAttribute('teks_pertanyaan')
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('kode_pertanyaan')
                    ->label('Kode')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('teks_pertanyaan')
                    ->label('Pertanyaan')
                    ->limit(60)
                    ->searchable(),
                TextColumn::make('tipe')
                    ->label('Tipe Jawaban')
                    ->badge(),
                \Filament\Tables\Columns\TextColumn::make('skor_maksimal')
                    ->label('Skor Maksimal')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->groups([
                \Filament\Tables\Grouping\Group::make('kategori.nama_kategori')
                    ->label('Kategori')
                    ->collapsible()
                    ->titlePrefixedWithLabel(false),
            ])
            ->defaultGroup('kategori.nama_kategori')
            ->collapsedGroupsByDefault(true)
            ->headerActions([
                CreateAction::make(),
                // AssociateAction::make(),
            ])
            ->recordActions([
                EditAction::make()->label('Ubah'),
                // DissociateAction::make()->label('Lepas Hubungan'),
                DeleteAction::make()->label('Hapus'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    // DissociateBulkAction::make()->label('Lepas Terpilih'),
                    DeleteBulkAction::make()->label('Hapus Terpilih'),
                ])->label('Aksi Massal'),
            ]);
    }
}
