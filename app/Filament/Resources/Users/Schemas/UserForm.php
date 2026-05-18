<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Data Akun')
                    ->description('Informasi login dan hak akses user.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        TextInput::make('password')
                            ->label('Password')
                            ->password()
                            ->revealable()
                            ->maxLength(255)
                            ->required(fn (string $operation, $get): bool => $operation === 'create' && $get('role') !== 'REVIEWER')
                            ->dehydrated(fn ($state) => filled($state))
                            ->helperText(fn (string $operation, $get): string => 
                                $get('role') === 'REVIEWER' 
                                    ? ($operation === 'create' ? 'Kosongkan untuk men-generate password otomatis.' : 'Kosongkan jika tidak ingin mengganti password.')
                                    : ($operation === 'create' ? '' : 'Kosongkan jika tidak ingin mengganti password.')
                            ),
                        Select::make('role')
                            ->options(function (string $operation) {
                                $options = [
                                    'ADMIN' => 'Admin',
                                    'REVIEWER' => 'Reviewer',
                                ];

                                if ($operation === 'edit') {
                                    $options['PESERTA'] = 'Peserta';
                                }

                                return $options;
                            })
                            ->required()
                            ->live(),
                        Select::make('status')
                            ->options([
                                'UNVERIFIED' => 'Unverified',
                                'ACTIVE' => 'Active',
                            ])
                            ->required()
                            ->visible(fn (string $operation): bool => $operation === 'edit'),
                    ]),

                Section::make('Detail Profil')
                    ->description('Informasi tambahan sesuai dengan role yang dipilih.')
                    ->columns(2)
                    ->visible(fn ($get) => in_array($get('role'), ['REVIEWER', 'PESERTA']))
                    ->schema([
                        // Fields for PESERTA
                        TextInput::make('nama_pt')
                            ->label('Nama Perguruan Tinggi')
                            ->required(fn ($get) => $get('role') === 'PESERTA')
                            ->visible(fn ($get) => $get('role') === 'PESERTA'),
                        Select::make('jenis_pt')
                            ->label('Jenis PT')
                            ->options([
                                'PTN' => 'Perguruan Tinggi Negeri (PTN)',
                                'PTS' => 'Perguruan Tinggi Swasta (PTS)',
                                'PTK' => 'Perguruan Tinggi Kedinasan (PTK)',
                            ])
                            ->required(fn ($get) => $get('role') === 'PESERTA')
                            ->visible(fn ($get) => $get('role') === 'PESERTA'),
                        TextInput::make('nama_pic')
                            ->label('Nama PIC')
                            ->required(fn ($get) => $get('role') === 'PESERTA')
                            ->visible(fn ($get) => $get('role') === 'PESERTA'),
                        TextInput::make('no_hp_pic')
                            ->label('No. HP PIC')
                            ->required(fn ($get) => $get('role') === 'PESERTA')
                            ->visible(fn ($get) => $get('role') === 'PESERTA'),
                        TextInput::make('jabatan_pic')
                            ->label('Jabatan PIC')
                            ->required(fn ($get) => $get('role') === 'PESERTA')
                            ->visible(fn ($get) => $get('role') === 'PESERTA'),

                        // Fields for REVIEWER
                        TextInput::make('nama_lengkap')
                            ->label('Nama Lengkap')
                            ->required(fn ($get) => $get('role') === 'REVIEWER')
                            ->visible(fn ($get) => $get('role') === 'REVIEWER')
                            ->maxLength(255),
                        TextInput::make('nip')
                            ->label('NIP / NIDN')
                            ->visible(fn ($get) => $get('role') === 'REVIEWER')
                            ->maxLength(255)
                            ->helperText('Opsional.'),
                    ]),
            ]);
    }
}
