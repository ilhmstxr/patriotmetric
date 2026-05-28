<?php

namespace App\Filament\Resources\Assessments;

use App\Filament\Resources\Assessments\Pages\CreateAssessment;
use App\Filament\Resources\Assessments\Pages\EditAssessment;
use App\Filament\Resources\Assessments\Pages\ListAssessments;
use App\Filament\Resources\Assessments\Schemas\AssessmentForm;
use App\Filament\Resources\Assessments\Tables\AssessmentsTable;
use App\Models\Assessment;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Schemas\Components\Section;

class AssessmentResource extends Resource
{
    protected static ?string $model = Assessment::class;

    protected static bool $shouldRegisterNavigation = true;

    protected static ?string $navigationLabel = 'Monitoring Assessment';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return AssessmentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AssessmentsTable::configure($table);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Utama')
                    ->schema([
                        TextEntry::make('institusi.nama_institusi')->label('Nama Institusi'),
                        TextEntry::make('nama_pic')->label('Nama PIC'),
                        TextEntry::make('status')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'UNVERIFIED'  => 'danger',
                                'ACTIVE'      => 'gray',
                                'IN_PROGRESS' => 'info',
                                'SUBMITTED'   => 'warning',
                                'GRADED'      => 'success',
                                'PUBLISHED'   => 'success',
                                default       => 'gray',
                            }),
                        TextEntry::make('total_skor_sistem')->label('Total Skor Sistem'),
                        TextEntry::make('total_skor_akhir')->label('Total Skor Akhir'),
                    ])->columns(3),

                Section::make('Biodata Instansi')
                    ->schema([
                        TextEntry::make('institusi.jenis_institusi')->label('Jenis Instansi'),
                        TextEntry::make('identitas.jml_mahasiswa')->label('Jumlah Mahasiswa'),
                        TextEntry::make('identitas.jml_dosen')->label('Jumlah Dosen'),
                        TextEntry::make('identitas.jml_tendik')->label('Jumlah Tendik'),
                        TextEntry::make('identitas.jml_prodi')->label('Jumlah Prodi'),
                        TextEntry::make('identitas.jml_fakultas')->label('Jumlah Fakultas'),
                        TextEntry::make('nama_pic')->label('Nama PIC'),
                        TextEntry::make('jabatan_pic')->label('Jabatan PIC'),
                        TextEntry::make('no_hp_pic')->label('No HP PIC'),
                        TextEntry::make('identitas.visi')->label('Visi')->columnSpanFull(),
                        TextEntry::make('identitas.misi')->label('Misi')->columnSpanFull(),
                    ])->columns(3),

                Section::make('Hasil Dokumen')
                    ->schema([
                        TextEntry::make('legal_documents_preview')
                            ->label('')
                            ->html()
                            ->columnSpanFull()
                            ->getStateUsing(function ($record) {
                                $docs = $record->identitas?->legal_documents;

                                if (empty($docs)) return '<p class="text-gray-500">Belum ada dokumen.</p>';

                                if (is_string($docs)) {
                                    $docs = json_decode($docs, true);
                                }

                                if (empty($docs) || !is_array($docs)) {
                                    return '<p class="text-gray-500">Belum ada dokumen.</p>';
                                }

                                $html = '<div class="space-y-2">';
                                foreach ($docs as $key => $path) {
                                    $label = ucfirst(str_replace('_', ' ', $key));
                                    $url = str_starts_with($path, 'http')
                                        ? $path
                                        : asset('storage/' . ltrim(str_replace('/storage/', '', $path), '/'));
                                    $html .= '<div class="flex items-center gap-2">';
                                    $html .= '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>';
                                    $html .= '<a href="' . e($url) . '" target="_blank" class="text-primary-600 hover:underline">' . e($label) . '</a>';
                                    $html .= '</div>';
                                }
                                $html .= '</div>';

                                return $html;
                            }),
                    ]),

                Section::make('Jawaban Rubrik')
                    ->schema([
                        RepeatableEntry::make('jawabans')
                            ->label('')
                            ->schema([
                                TextEntry::make('pertanyaan.teks_pertanyaan')->label('Pertanyaan'),
                                TextEntry::make('jawaban_teks')
                                    ->label('Jawaban')
                                    ->formatStateUsing(function ($state) {
                                        if (empty($state)) {
                                            return '-';
                                        }
                                        
                                        $decoded = is_string($state) ? json_decode($state, true) : $state;
                                        
                                        if (is_array($decoded)) {
                                            if (isset($decoded['total_poin'])) {
                                                $parts = [];
                                                foreach (['lokal', 'regional', 'nasional', 'internasional'] as $skala) {
                                                    if (isset($decoded[$skala])) {
                                                        $nilai = is_array($decoded[$skala]) ? ($decoded[$skala]['nilai'] ?? 0) : $decoded[$skala];
                                                        if ($nilai > 0) {
                                                            $parts[] = ucfirst($skala) . ": " . $nilai;
                                                        }
                                                    }
                                                }
                                                return implode(', ', $parts) . " (Total Poin: " . ($decoded['total_poin'] ?? 0) . ")";
                                            }

                                            $raw = $decoded['raw_input'] ?? '';
                                            $calc = $decoded['calculated_percentage'] ?? $decoded['calculated'] ?? '';

                                            if ($raw !== '' && $calc !== '') {
                                                return "{$raw} (Kalkulasi: {$calc}%)";
                                            }
                                            if ($raw !== '') {
                                                return (string) $raw;
                                            }
                                            if ($calc !== '') {
                                                return (string) $calc;
                                            }
                                            
                                            return json_encode($decoded);
                                        }
                                        
                                        return (string) $state;
                                    }),
                                TextEntry::make('tautan_bukti_drive')->label('Tautan Bukti')
                                    ->url(fn($state) => is_string($state) && filter_var($state, FILTER_VALIDATE_URL) ? $state : null)
                                    ->openUrlInNewTab()
                                    ->color('primary'),
                                TextEntry::make('skor_sistem')->label('Skor Sistem'),
                                TextEntry::make('skor_validasi_reviewer')
                                    ->label('Skor Reviewer')
                                    ->placeholder('(kosong / belum di review)'),
                                TextEntry::make('note_reviewer')
                                    ->label('Note Reviewer')
                                    ->placeholder('(kosong / belum di review)'),
                            ])
                            ->columns(2)
                            ->columnSpanFull()
                    ])
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAssessments::route('/'),
            'create' => CreateAssessment::route('/create'),
            'view' => \App\Filament\Resources\Assessments\Pages\ViewAssessment::route('/{record}'),
            'edit' => EditAssessment::route('/{record}/edit'),
        ];
    }
}
