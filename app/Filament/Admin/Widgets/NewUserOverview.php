<?php

namespace App\Filament\Admin\Widgets;

use App\Models\User;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class NewUserOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            Stat::make('New Users Today', self::getUserRegisteredToday()),
            Stat::make('New Users This Week', self::getUserRegisteredThisWeek()),
            Stat::make('New Users This Month', self::getUserRegisteredThisMonth()),
        ];
    }

    protected static function getUserRegisteredToday(): int
    {
        return User::whereDate('created_at', Carbon::today())->count();
    }

    protected static function getUserRegisteredThisWeek(): int
    {
        return User::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
    }

    protected static function getUserRegisteredThisMonth(): int
    {
        return User::whereMonth('created_at', Carbon::now()->month)
                    ->whereYear('created_at', Carbon::now()->year)
                    ->count();
    }

}
