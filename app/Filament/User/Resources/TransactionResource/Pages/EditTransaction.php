<?php

namespace App\Filament\User\Resources\TransactionResource\Pages;

use App\Filament\User\Resources\TransactionResource;
use App\Models\Airlines;
use App\Models\Catering;
use App\Models\PackageType;
use App\Models\Transportation;
use Exception;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditTransaction extends EditRecord
{
    protected static string $resource = TransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getFormActions(): array
    {
        return [];
    }

    protected function getSaveFormAction(): Action
    {
        return Action::make('save')
            ->label('Submit')
            ->submit('save')
            ->keyBindings(['mod+s']);
    }

    public function save(bool $shouldRedirect = true, bool $shouldSendSavedNotification = true): void
    {
        $this->authorizeAccess();

        try {
            $data = $this->form->getState();

            $qtyDifference = $data['qty'] - $this->record->qty;

            if ($qtyDifference > 0) {
                $this->decrementStock($qtyDifference);
            } elseif ($qtyDifference < 0) {
                $this->incrementStock(-$qtyDifference);
            }
            $this->handleRecordUpdate($this->getRecord(), $data);

            $this->getSavedNotification()?->send();
            $this->redirect(TransactionResource::getUrl('index'));
        } catch (Exception $e) {
            Notification::make()
                ->title('Transaction failed')
                ->body($e->getMessage())
                ->danger()
                ->send();

            return;
        }
    }


    protected function incrementStock(int $qty): void
    {
        $travelPackage = $this->record->travel_package;

        if ($travelPackage->id_airline != null) {
            Airlines::where('id', $travelPackage->id_airline)->increment('stock', $qty);
        }

        if ($travelPackage->id_catering != null) {
            Catering::where('id', $travelPackage->id_catering)->increment('stock', $qty);
        }

        // if ($travelPackage->id_hotel != null) {
        //     Hotel::where('id', $travelPackage->id_hotel)->increment('stock', $qty);
        // }

        if ($travelPackage->id_transportation != null) {
            Transportation::where('id', $travelPackage->id_transportation)->increment('stock', $qty);
        }

        PackageType::where('id', $travelPackage->id)->increment('stock', $qty);
    }

    protected function decrementStock(int $qty): void
    {
        $travelPackage = $this->record->travel_package;

        if ($travelPackage->id_airline != null) {
            Airlines::where('id', $travelPackage->id_airline)->decrement('stock', $qty);
        }

        if ($travelPackage->id_catering != null) {
            Catering::where('id', $travelPackage->id_catering)->decrement('stock', $qty);
        }

        // if ($travelPackage->id_hotel != null) {
        //     Hotel::where('id', $travelPackage->id_hotel)->decrement('stock', $qty);
        // }

        if ($travelPackage->id_transportation != null) {
            Transportation::where('id', $travelPackage->id_transportation)->decrement('stock', $qty);
        }

        PackageType::where('id', $travelPackage->id)->decrement('stock', $qty);
    }
}
