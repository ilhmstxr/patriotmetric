<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseStatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseStatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Institusi', \App\Models\User::where('role', 'submiter')->count()),
            Stat::make('Menunggu Review', \App\Models\pengumpulan::whereIn('status', ['submitted', 'reviewing'])->count()),
            Stat::make('Selesai Divalidasi', \App\Models\pengumpulan::where('status', 'validated')->count()),
        ];
    }
}
