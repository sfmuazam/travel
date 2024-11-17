<?php

namespace App\Filament\Admin\Resources\PaymentSettingsResource\Pages;

use App\Filament\Admin\Resources\PaymentSettingsResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;

class EditPaymentSettings extends EditRecord
{
    protected static string $resource = PaymentSettingsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getSaveFormAction(): Action
    {
        return Action::make('save')
            ->label('Submit')
            ->submit('save')
            ->keyBindings(['mod+s']);
    }
}
