<?php

namespace App\Filament\Admin\Resources\FinancialResource\Pages;

use App\Filament\Admin\Resources\FinancialResource;
use App\Models\Commission;
use Exception;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\DB;

class EditFinancial extends EditRecord
{
    protected static string $resource = FinancialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
        ];
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
            $this->callHook('beforeValidate');

            $data = $this->form->getState();

            DB::transaction(function () use ($data) {
                $record = $this->getRecord();
                $this->callHook('afterValidate');
                // dd($record->transaction);
                // dd($data);
                $data = $this->mutateFormDataBeforeSave($data);

                $this->callHook('beforeSave');

                $this->handleRecordUpdate($this->getRecord(), $data);
                if ($data['status'] == 'completed') {
                    Commission::where('id_transaction', $record->transaction->id)->update([
                        'status' => 'completed',
                        'notes' => 'in',
                        'updated_at' => now(),
                    ]);
                } else {
                    Commission::where('id_transaction', $record->transaction->id)->update([
                        'status' => 'pending',
                        'notes' => 'in',
                        'updated_at' => now(),
                    ]);
                }
                $this->callHook('afterSave');

                Notification::make()
                    ->title('Transaction success')
                    ->body('Transaction has been updated successfully')
                    ->success()
                    ->send();
            });
        } catch (Exception $e) {
            Notification::make()
                ->title('Transaction failed')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }
}
