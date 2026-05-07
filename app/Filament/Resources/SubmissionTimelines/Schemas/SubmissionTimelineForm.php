<?php

namespace App\Filament\Resources\SubmissionTimelines\Schemas;

use App\Models\SubmissionTimeline;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Validation\Rule;

class SubmissionTimelineForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('tahun_periode')
                    ->label('Tahun Periode')
                    ->numeric()
                    ->required()
                    ->minValue(2000)
                    ->maxValue(2100)
                    ->default((int) date('Y'))
                    ->rule(function (?SubmissionTimeline $record) {
                        return Rule::unique('submission_timelines', 'tahun_periode')
                            ->ignore($record?->id);
                    })
                    ->helperText('Satu baris timeline per tahun periode (unik).'),
                // DateTimePicker::make('opens_at')
                //     ->label('Dibuka Mulai')
                //     ->seconds(false)
                //     ->helperText('Kosongkan jika ingin selalu terbuka sejak awal.'),
                DateTimePicker::make('closes_at')
                    ->label('Ditutup Pada')
                    ->seconds(false)
                    ->after('opens_at')
                    ->helperText('Kosongkan jika tidak ada deadline.'),
                // Toggle::make('is_locked')
                //     ->label('Kunci Manual (Force Lock)')
                //     ->helperText('Aktifkan untuk segera mengunci submission tanpa menunggu deadline.')
                //     ->default(false),

            ]);
    }
}
