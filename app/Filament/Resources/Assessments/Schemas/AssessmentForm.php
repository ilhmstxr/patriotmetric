<?php

namespace App\Filament\Resources\Assessments\Schemas;

use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class AssessmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Akun Peserta')
                    ->description('Informasi login akun peserta (Email & Password).')
                    ->schema([
                        TextInput::make('user_email')
                            ->label('Email Peserta')
                            ->email()
                            ->required()
                            ->default(fn ($record) => $record?->user?->email),
                        TextInput::make('user_password')
                            ->label('Password Baru')
                            ->password()
                            ->nullable()
                            ->placeholder('Kosongkan jika tidak ingin mengubah password'),
                    ])->columns(2),

                Section::make('Status & Reviewer')
                    ->description('Kelola status penilaian dan reviewer yang ditugaskan.')
                    ->schema([
                        Select::make('reviewer_id')
                            ->relationship('reviewer', 'nama_lengkap')
                            ->searchable()
                            ->placeholder('Pilih Reviewer'),
                        Select::make('status')
                            ->options([
                                'UNVERIFIED' => 'Unverified',
                                'ACTIVE' => 'Active',
                                'IN_PROGRESS' => 'In Progress',
                                'SUBMITTED' => 'Submitted',
                                'GRADED' => 'Graded',
                                'PUBLISHED' => 'Published',
                            ])
                            ->required(),
                    ])->columns(2),

                Section::make('Profil Instansi & PIC')
                    ->description('Detail profil perguruan tinggi dan PIC yang bertanggung jawab.')
                    ->hidden(fn (string $operation) => $operation === 'edit')
                    ->schema([
                        TextInput::make('institusi_nama')
                            ->label('Nama Instansi')
                            ->required()
                            ->default(fn ($record) => $record?->institusi?->nama_institusi),
                        Select::make('institusi_jenis')
                            ->label('Jenis Instansi')
                            ->options([
                                'PTN' => 'Perguruan Tinggi Negeri (PTN)',
                                'PTS' => 'Perguruan Tinggi Swasta (PTS)',
                                'PTK' => 'Perguruan Tinggi Kedinasan (PTK)',
                            ])
                            ->required()
                            ->default(fn ($record) => $record?->institusi?->jenis_institusi),
                        TextInput::make('nama_pic')
                            ->label('Nama PIC')
                            ->required(),
                        TextInput::make('jabatan_pic')
                            ->label('Jabatan PIC'),
                        TextInput::make('no_hp_pic')
                            ->label('No. HP PIC')
                            ->required(),
                    ])->columns(3),

                Section::make('Demografi & Visi Misi')
                    ->description('Informasi demografis institusi beserta visi dan misi.')
                    ->hidden(fn (string $operation) => $operation === 'edit')
                    ->schema([
                        TextInput::make('identitas_jml_mahasiswa')
                            ->label('Jumlah Mahasiswa')
                            ->numeric()
                            ->default(fn ($record) => $record?->identitas?->jml_mahasiswa ?? 0),
                        TextInput::make('identitas_jml_dosen')
                            ->label('Jumlah Dosen')
                            ->numeric()
                            ->default(fn ($record) => $record?->identitas?->jml_dosen ?? 0),
                        TextInput::make('identitas_jml_tendik')
                            ->label('Jumlah Tendik')
                            ->numeric()
                            ->default(fn ($record) => $record?->identitas?->jml_tendik ?? 0),
                        TextInput::make('identitas_jml_prodi')
                            ->label('Jumlah Program Studi')
                            ->numeric()
                            ->default(fn ($record) => $record?->identitas?->jml_prodi ?? 0),
                        TextInput::make('identitas_jml_fakultas')
                            ->label('Jumlah Fakultas')
                            ->numeric()
                            ->default(fn ($record) => $record?->identitas?->jml_fakultas ?? 0),
                        Textarea::make('identitas_visi')
                            ->label('Visi Instansi')
                            ->rows(3)
                            ->columnSpanFull()
                            ->default(fn ($record) => $record?->identitas?->visi),
                        Textarea::make('identitas_misi')
                            ->label('Misi Instansi')
                            ->rows(3)
                            ->columnSpanFull()
                            ->default(fn ($record) => $record?->identitas?->misi),
                    ])->columns(3),
            ]);
    }
}
