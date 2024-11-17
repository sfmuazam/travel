<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Financial;
use Midtrans\Notification;
use Log;

class MidtransController extends Controller
{
    public function handleNotification(Request $request)
    {
        // Setup konfigurasi Midtrans
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        $notification = new Notification();

        $transactionStatus = $notification->transaction_status;
        $fraudStatus = $notification->fraud_status;
        $orderId = $notification->order_id;

        $transaction = Transaction::where('id', str_replace('TRA-', '', $orderId))->first();

        if ($transaction) {
            if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
                $transaction->update([
                    'status' => 'completed'
                ]);
                $transaction->financial->status = 'completed';

            } elseif ($transactionStatus == 'pending') {
                $transaction->update([
                    'status' => 'pending'
                ]);
                $transaction->financial->status = 'pending';

            } elseif ($transactionStatus == 'deny' || $transactionStatus == 'expire' || $transactionStatus == 'canceled') {
                $transaction->update([
                    'status' => 'canceled'
                ]);
                $transaction->financial->status = 'canceled';
            }

            $transaction->save();

            return response()->json(['message' => 'Notification received and processed'], 200);
        } else {
            return response()->json(['message' => 'Transaction not found'], 404);
        }
    }
}
