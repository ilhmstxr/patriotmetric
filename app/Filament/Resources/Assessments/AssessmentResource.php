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
                \Filament\Schemas\Components\Section::make('Informasi Utama')
                    ->schema([
                        TextEntry::make('institusi.nama_institusi')->label('Nama Peserta'),
                        TextEntry::make('status')->badge(),
                        TextEntry::make('total_skor_akhir')->label('Total Skor Akhir'),
                    ])->columns(3),

                \Filament\Schemas\Components\Section::make('Biodata Instansi')
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

                \Filament\Schemas\Components\Section::make('Hasil Dokumen Setelah Verifikasi')
                    ->schema([
                        TextEntry::make('identitas.legal_documents')
                            ->label('Dokumen Verifikasi (Klik untuk membuka)')
                            ->formatStateUsing(function ($state) {
                                if (empty($state) || !is_array($state)) return '-';
                                $html = '<ul class="list-disc ml-5">';
                                foreach ($state as $key => $link) {
                                    $name = is_numeric($key) ? 'Dokumen '.($key+1) : ucfirst(str_replace('_', ' ', $key));
                                    // Handle array values gracefully if needed, though they should be string URLs
                                    $href = is_string($link) ? $link : '#';
                                    $html .= "<li><a href=\"{$href}\" target=\"_blank\" style=\"color:blue; text-decoration:underline;\">{$name}</a></li>";
                                }
                                $html .= '</ul>';
                                return new \Illuminate\Support\HtmlString($html);
                            })
                            ->html()
                            ->columnSpanFull()
                    ]),

                \Filament\Schemas\Components\Section::make('Jawaban Rubrik')
                    ->schema([
                        RepeatableEntry::make('jawabans')
                            ->label('')
                            ->schema([
                                TextEntry::make('pertanyaan.teks_pertanyaan')->label('Pertanyaan'),
                                TextEntry::make('jawaban_teks')->label('Jawaban')->formatStateUsing(fn($state) => is_string($state) ? $state : json_encode($state)),
                                TextEntry::make('tautan_bukti_drive')->label('Tautan Bukti')
                                    ->url(fn($state) => is_string($state) && filter_var($state, FILTER_VALIDATE_URL) ? $state : null)
                                    ->openUrlInNewTab()
                                    ->color('primary'),
                                TextEntry::make('skor_sistem')->label('Skor Sistem'),
                                TextEntry::make('skor_validasi_reviewer')->label('Skor Reviewer'),
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
