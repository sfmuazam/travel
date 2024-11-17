<?php

namespace App\Filament\Admin\Resources\CateringResource\Pages;

use App\Filament\Admin\Resources\CateringResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCaterings extends ListRecords
{
    protected static string $resource = CateringResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
