<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\PaymentSuccess;
use App\Models\Commission;
use App\Models\Transaction;
use App\Schemas\FlipSenderBankSchema;
use App\Services\FlipService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class FlipController extends Controller
{
    public function paymentTest()
    {
        $flipTransaction = new FlipService;
        $user = Auth::user();
        $transactionResponse = $flipTransaction->generateBillLinkCurl(
            [
                'name' => $user->name,
                'email' => $user->email,
            ],
            [
                'code' => 'mandiri',
                'type' => 'virtual_account',
            ],
            100000,
            '2024-06-19 23:00',
            'TRA-212',
            1
        );
        return response()->json($transactionResponse);
    }

    public function callbackAcceptPayment(Request $request)
    {
        // $api_log = new ApiLog();
        // $api_log->mode = 'FLIP VALIDATION';
        // $api_log->request = json_encode($request->post());
        // $api_log->save();
        $flip_validation_token = config('flip.validation_token');
        Log::debug($request->post());
        $rawdata = $request->post('data');
        $token = $request->post('token');

        $data = json_decode($rawdata);
        // $data = $rawdata;

        $bill_title = $data->bill_title;
        Log::debug($bill_title);
        $status = $data->status;
        Log::debug($status);
        if ($flip_validation_token == $token) {
            if ($status == 'SUCCESSFUL') {
                Log::info('masuk sukses');
                if (strpos($bill_title, 'TRA') === 0) {
                    Log::info('masuk TRA');
                    $id = substr($bill_title, 4);
                    Log::debug($id);
                    $transaction = Transaction::find($id);
                    Log::debug('masuk transaction');
                    Log::debug($transaction);
                    if ($transaction->payment_status == 'full') {
                        $transaction->financial->status = 'completed';
                        Commission::where('id_transaction', $transaction->id)->update([
                            'status' => 'completed',
                            'notes' => 'in',
                            'updated_at' => now(),
                        ]);
                        Mail::to($transaction->user->email)->send(new PaymentSuccess($transaction));
                        Log::info('email completed');
                    }
                    if ($transaction->payment_status == 'partial'){
                        $transaction->financial->status = 'dp_completed';
                        Mail::to($transaction->user->email)->send(new PaymentSuccess($transaction));
                    }
                    $transaction->financial->save();
                } else if (strpos($bill_title, 'TRP') === 0) {
                    Log::info('masuk TRP');
                    $id = substr($bill_title, 4);
                    Log::debug($id);
                }
            } else if ($status == 'FAILED') {
                Log::info('masuk failed');
            } else {
            }
        }
    }

    public function callbackTransaction(Request $request)
    {
        $flip_validation_token = config('flip.validation_token');
        Log::debug($request->post());
        $rawdata = $request->post('data');
        $token = $request->post('token');
    }
}
