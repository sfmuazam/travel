<?php

namespace App\Filament\Admin\Resources\TravelPackageResource\Pages;

use App\Filament\Admin\Resources\TravelPackageResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTravelPackage extends CreateRecord
{
    protected static string $resource = TravelPackageResource::class;

    protected function getFormActions(): array
    {
        return [];
    }
}
