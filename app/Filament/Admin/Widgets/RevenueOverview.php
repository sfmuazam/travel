<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Financial;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class RevenueOverview extends BaseWidget
{
    protected static ?int $sort = 3;

    protected function getStats(): array
    {
        return [
            Stat::make('Revenue Today', 'Rp' . number_format(self::getRevenueToday(), 0, ',', '.')),
            Stat::make('Revenue This Week', 'Rp' . number_format(self::getRevenueThisWeek(), 0, ',', '.')),
            Stat::make('Revenue This Month', 'Rp' . number_format(self::getRevenueThisMonth(), 0, ',', '.')),
        ];
    }

    protected static function getRevenueToday(): float
    {
        return Financial::whereDate('created_at', Carbon::today())
            ->where('status', 'completed')
            ->sum('amount');
    }
    protected static function getRevenueThisWeek(): float
    {
        return Financial::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->where('status', 'completed')

            ->sum('amount');
    }

    protected static function getRevenueThisMonth(): float
    {
        return Financial::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->where('status', 'completed')
            ->sum('amount');
    }
}
