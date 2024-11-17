<?php

namespace App\Filament\Admin\Resources\HotelResource\Pages;

use App\Filament\Admin\Resources\HotelResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;

class EditHotel extends EditRecord
{
    protected static string $resource = HotelResource::class;

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
