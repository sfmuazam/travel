<?php

namespace App\Filament\Admin\Resources\PaymentSettingsResource\Pages;

use App\Filament\Admin\Resources\PaymentSettingsResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;

class CreatePaymentSettings extends CreateRecord
{
    protected static string $resource = PaymentSettingsResource::class;


    protected function getCreateFormAction(): Action
    {
        return Action::make('create')
            ->label('Submit')
            ->submit('create')
            ->keyBindings(['mod+s']);
    }
}
