<?php

namespace App\Filament\Admin\Resources\TransportationResource\Pages;

use App\Filament\Admin\Resources\TransportationResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;

class EditTransportation extends EditRecord
{
    protected static string $resource = TransportationResource::class;

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
