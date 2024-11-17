<?php

namespace App\Filament\Admin\Resources\PackageTermsResource\Pages;

use App\Filament\Admin\Resources\PackageTermsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPackageTerms extends ListRecords
{
    protected static string $resource = PackageTermsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
