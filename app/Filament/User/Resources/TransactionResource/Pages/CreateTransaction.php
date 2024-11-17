<?php

namespace App\Filament\User\Resources\TransactionResource\Pages;

use App\Filament\User\Resources\TransactionResource;
use App\Mail\TransactionDetail;
use App\Models\Airlines;
use App\Models\BranchFinancial;
use App\Models\Catering;
use App\Models\Commission;
use App\Models\Hotel;
use App\Models\Invoice;
use App\Models\PackageType;
use App\Models\Transaction;
use App\Models\Transportation;
use App\Models\TravelPackage;
use App\Models\User;
use App\Services\MidtransService;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CreateTransaction extends CreateRecord
{
    protected static string $resource = TransactionResource::class;

    public function create(bool $another = false): void
    {
        $this->authorizeAccess();

        $data = $this->form->getState();
        try {
            DB::transaction(function () use ($data) {
                $qty = $data['qty'];
                $packageType = PackageType::find($data['travel_package_type_id']);
                $travel = TravelPackage::find($data['travel_package_id']);
                $user = User::find(auth()->user()->id);
                $data['by_id'] = auth()->user()->id;
                $midtransService = new MidtransService;

                $insufficientStock = false;

                if ($travel->id_airline != null) {
                    if ($travel->airline->stock >= $qty) {
                        $travel->airline->decrement('stock', $qty);
                    } else {
                        $insufficientStock = true;
                    }
                }

                if ($travel->id_catering != null) {
                    if ($travel->catering->stock >= $qty) {
                        $travel->catering->decrement('stock', $qty);
                    } else {
                        $insufficientStock = true;
                    }
                }

                // if ($travel->id_hotel != null) {
                //     if ($travel->hotel->stock >= $qty) {
                //         $travel->hotel->decrement('stock', $qty);
                //     } else {
                //         $insufficientStock = true;
                //     }
                // }

                if ($travel->id_transportation != null) {
                    if ($travel->transportation->stock >= $qty) {
                        $travel->transportation->decrement('stock', $qty);
                    } else {
                        $insufficientStock = true;
                    }
                }

                if ($insufficientStock) {
                    Notification::make()
                        ->title('Transaction failed: Insufficient stock')
                        ->body('Please check every stock and try again')
                        ->danger()
                        ->send();
                    return;
                }
                $tomorrow = Carbon::now()->addDay()->format('Y-m-d H:i');

                $transaction = $this->record = $this->handleRecordCreation($data);
                $redirectUrl = route('filament.user.resources.transactions.index');
                // $redirectUrl = 'https://e788-103-170-22-113.ngrok-free.app';
                Log::debug('Unencoded redirect URL: ' . $redirectUrl);

                // if ($data['payment_method'] == 'va') {
                //     $transactionResponse = $flipTransaction->generateBillLinkCurl(
                //         [
                //             'name' => config('app.name').'-'.$user->name,
                //             'email' => $user->email,
                //         ],
                //         [
                //             'code' => $data['bank_va'],
                //             'type' => 'virtual_account',
                //         ],
                //         $data['total_price'],
                //         $tomorrow,
                //         'TRA-' . $transaction->id,
                //         1,
                //         $redirectUrl

                //     );
                //     $paymentUrl = $transactionResponse->payment_url;
                //     // dd($paymentUrl);
                //     $transaction->update([
                //         'payment_url' => $paymentUrl,
                //     ]);
                // }

                if ($data['payment_method'] == 'midtrans') {
                    $paymentUrl = $midtransService->createTransaction(
                        'TRA-' . $transaction->id,
                        $data['total_price'],
                        $user
                    );
        
                    $transaction->update([
                        'payment_url' => $paymentUrl,
                    ]);
                }

                $this->form->model($this->getRecord())->saveRelationships();

                $financial = $transaction->financial()->create([
                    'type' => 'in',
                    'status' => 'pending',
                    'amount' => $data['total_price'],
                    'id_transaction' => $transaction->id,
                ]);

                Invoice::create([
                    'transaction_id' => $transaction->id,
                    'status' => 'pending',
                ]);

                PackageType::where('id', $data['travel_package_type_id'])->decrement('stock', $qty);
                return redirect($paymentUrl);
                
                Mail::to($user->email)->send(new TransactionDetail($transaction));

                Notification::make()
                    ->title('Saved successfully')
                    ->success()
                    ->send();
                if ($data['payment_method'] == 'va') {
                    // return redirect($paymentUrl);
                    $this->redirect($paymentUrl);
                } else {
                    $this->redirect(TransactionResource::getUrl('index'));
                }
            });
        } catch (\Exception $e) {
            Notification::make()
                ->title('Transaction failed')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    protected function getFormActions(): array
    {
        return [];
    }
}
