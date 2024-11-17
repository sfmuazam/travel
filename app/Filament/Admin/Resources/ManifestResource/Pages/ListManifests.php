<?php

namespace App\Filament\Admin\Resources\ManifestResource\Pages;

use App\Filament\Admin\Resources\ManifestResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListManifests extends ListRecords
{
    protected static string $resource = ManifestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
