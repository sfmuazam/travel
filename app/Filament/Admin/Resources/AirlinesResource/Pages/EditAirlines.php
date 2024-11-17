<?php

namespace App\Filament\Admin\Resources\AirlinesResource\Pages;

use App\Filament\Admin\Resources\AirlinesResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;

class EditAirlines extends EditRecord
{
    protected static string $resource = AirlinesResource::class;

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
