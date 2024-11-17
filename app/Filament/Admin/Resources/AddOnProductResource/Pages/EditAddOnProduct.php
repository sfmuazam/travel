<?php

namespace App\Filament\Admin\Resources\AddOnProductResource\Pages;

use App\Filament\Admin\Resources\AddOnProductResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;

class EditAddOnProduct extends EditRecord
{
    protected static string $resource = AddOnProductResource::class;

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
