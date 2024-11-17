<?php

namespace App\Filament\User\Widgets;

use App\Models\Commission;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{

    public static function canView(): bool
    {
        if (auth()->check() && auth()->user()->isAgentVerified()) {
            return true;
        } else {
            return false;
        }
    }
}
