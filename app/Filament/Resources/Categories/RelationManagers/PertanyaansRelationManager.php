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
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;

class PertanyaansRelationManager extends RelationManager
{
    protected static string $relationship = 'pertanyaans';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
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
                        'teks_singkat' => 'Teks Singkat',
                    ])
                    ->live()
                    ->required(),
                /* TextInput::make('skor_maksimal')
                    ->label('Skor Maksimal')
                    ->numeric()
                    ->default(0)
                    ->required(), */
                Repeater::make('OpsiJawaban')
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
                    ->visible(fn ($get): bool => $get('tipe') === 'pilihan_ganda')
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                //
            ])
            ->recordTitleAttribute('teks_pertanyaan')
            ->columns([
                TextColumn::make('kode_pertanyaan')
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
                /* TextColumn::make('skor_maksimal')
                    ->label('Skor Maksimal')
                    ->sortable(), */
            ])
            ->filters([
                //
            ])
            ->groups([
                Group::make('kategori.nama_kategori')
                    ->label('Kategori')
                    ->collapsible()
                    ->titlePrefixedWithLabel(false),
            ])
            ->defaultGroup('kategori.nama_kategori')
            ->collapsedGroupsByDefault(false)
            ->headerActions([
                //
            ])
            ->recordActions([
                Action::make('manageOptions')
                    ->label('Kelola Opsi')
                    ->icon('heroicon-o-list-bullet')
                    ->color('info')
                    ->url(fn ($record) => \App\Filament\Resources\Pertanyaans\PertanyaanResource::getUrl('edit', ['record' => $record])),
            ])
            ->defaultPaginationPageOption(5)
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ])->label('Aksi Massal'),
            ]);
    }
}
