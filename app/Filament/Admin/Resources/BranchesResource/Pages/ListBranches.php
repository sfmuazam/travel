<?php

namespace App\Filament\Admin\Resources\BranchesResource\Pages;

use App\Filament\Admin\Resources\BranchesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBranches extends ListRecords
{
    protected static string $resource = BranchesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
