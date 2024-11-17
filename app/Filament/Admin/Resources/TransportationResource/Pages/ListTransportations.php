<?php

namespace App\Filament\Admin\Resources\TransportationResource\Pages;

use App\Filament\Admin\Resources\TransportationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTransportations extends ListRecords
{
    protected static string $resource = TransportationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
