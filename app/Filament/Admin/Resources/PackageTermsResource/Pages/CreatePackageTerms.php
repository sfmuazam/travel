<?php

namespace App\Filament\Admin\Resources\PackageTermsResource\Pages;

use App\Filament\Admin\Resources\PackageTermsResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;

class CreatePackageTerms extends CreateRecord
{
    protected static string $resource = PackageTermsResource::class;

    protected function getCreateFormAction(): Action
    {
        return Action::make('create')
            ->label('Submit')
            ->submit('create')
            ->keyBindings(['mod+s']);
    }
}
