<?php

namespace App\Filament\Admin\Resources\TravelPackageResource\Pages;

use App\Filament\Admin\Resources\TravelPackageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTravelPackages extends ListRecords
{
    protected static string $resource = TravelPackageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
