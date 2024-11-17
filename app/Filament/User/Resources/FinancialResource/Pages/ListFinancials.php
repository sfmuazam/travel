<?php

namespace App\Filament\User\Resources\FinancialResource\Pages;

use App\Filament\User\Resources\FinancialResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFinancials extends ListRecords
{
    protected static string $resource = FinancialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
