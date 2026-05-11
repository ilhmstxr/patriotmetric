<?php

namespace App\Filament\Widgets;

use App\Models\Assessment;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseStatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseStatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Institusi', User::where('role', 'submiter')->count()),
            Stat::make('Menunggu Review', Assessment::whereIn('status', ['submitted', 'reviewing'])->count()),
            Stat::make('Selesai Divalidasi', Assessment::where('status', 'validated')->count()),
        ];
    }
}
