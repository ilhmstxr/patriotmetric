<?php

namespace App\Filament\Widgets;

use App\Models\Pengumpulan;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseStatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseStatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Institusi', User::where('role', 'submiter')->count()),
            Stat::make('Menunggu Review', Pengumpulan::whereIn('status', ['submitted', 'reviewing'])->count()),
            Stat::make('Selesai Divalidasi', Pengumpulan::where('status', 'validated')->count()),
        ];
    }
}
