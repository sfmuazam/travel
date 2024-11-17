<?php

namespace App\Filament\Admin\Resources\AddOnProductResource\Pages;

use App\Filament\Admin\Resources\AddOnProductResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAddOnProducts extends ListRecords
{
    protected static string $resource = AddOnProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
