<?php

namespace App\Filament\Admin\Resources\BranchesResource\Pages;

use App\Filament\Admin\Resources\BranchesResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;

class EditBranches extends EditRecord
{
    protected static string $resource = BranchesResource::class;

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
