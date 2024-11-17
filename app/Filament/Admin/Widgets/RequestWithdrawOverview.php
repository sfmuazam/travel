<?php

namespace App\Filament\Admin\Widgets;

use App\Models\WithdrawCommission;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class RequestWithdrawOverview extends BaseWidget
{
    protected static ?int $sort = 4;

    protected function getStats(): array
    {
        return [
            Stat::make('Pending Requests Withdraw', self::getTransactionsToday()),
        ];
    }

    protected static function getTransactionsToday(): int
    {
        return WithdrawCommission::where('status', 'pending')
            ->count();
    }
}
