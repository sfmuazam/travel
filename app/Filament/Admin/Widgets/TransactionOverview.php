<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Transaction;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TransactionOverview extends BaseWidget
{
    protected static ?int $sort = 2;
    
    protected function getStats(): array
    {
        return [
            Stat::make('Pending Transactions Today', self::getTransactionsToday('pending'))
            ,
            Stat::make('Pending Transactions This Week', self::getTransactionsThisWeek('pending')),
            Stat::make('Pending Transactions This Month', self::getTransactionsThisMonth('pending')),
            Stat::make('Completed Transactions Today', self::getTransactionsToday('completed')),
            Stat::make('Completed Transactions This Week', self::getTransactionsThisWeek('completed')),
            Stat::make('Completed Transactions This Month', self::getTransactionsThisMonth('completed')),
        ];
    }


    protected static function getTransactionsToday(string $status): int
    {
        return Transaction::whereDate('created_at', Carbon::today())
            ->whereHas('financial', function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->count();
    }

    protected static function getTransactionsThisWeek(string $status): int
    {
        return Transaction::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->whereHas('financial', function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->count();
    }

    protected static function getTransactionsThisMonth(string $status): int
    {
        return Transaction::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->whereHas('financial', function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->count();
    }
}
