<?php

namespace App\Filament\User\Resources\FinancialResource\Pages;

use App\Filament\User\Resources\FinancialResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;

class EditFinancial extends EditRecord
{
    protected static string $resource = FinancialResource::class;

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
