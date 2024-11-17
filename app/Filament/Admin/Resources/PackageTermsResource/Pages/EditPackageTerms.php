<?php

namespace App\Filament\Admin\Resources\PackageTermsResource\Pages;

use App\Filament\Admin\Resources\PackageTermsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPackageTerms extends EditRecord
{
    protected static string $resource = PackageTermsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
