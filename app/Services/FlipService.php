<?php

namespace App\Services;

use App\Schemas\FlipTransactionSchema;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class FlipService
{
    private $url;
    private $api_key;

    public function __construct()
    {
        $this->url = config('flip.url');
        $this->api_key = config('flip.api_key');
    }

    public function generateBillLinkCurl($customerData, $bankData, $amount, $expirationDate, $transactionCode, $is_flexible = 0, $redirectUrl = null)
    {
        Log::debug($bankData);
        $transaction_url = $this->url . '/pwf/bill';
        $fields = 'title=' . rawurlencode($transactionCode) .
            '&amount=' . $amount .
            '&type=' . FlipTransactionSchema::SINGLE .
            '&expired_date=' . rawurlencode($expirationDate) .
            '&is_phone_number_required=0' .
            '&step=3' .
            '&sender_name=' . rawurlencode($customerData['name']) .
            '&sender_email=' . rawurlencode($customerData['email']) .
            '&sender_bank=' . $bankData['code'] .
            '&sender_bank_type=' . $bankData['type'];
        if ($redirectUrl) {
            $fields .= '&redirect_url=' . $redirectUrl;
        }

        if (!$is_flexible) {
            $fields .= '&is_address_required=1';
            $fields .= '&sender_address=' . rawurlencode($customerData['address']);
        }

        Log::debug($fields);

        $curl = curl_init();
        Log::debug($transaction_url);
        curl_setopt_array($curl, array(
            CURLOPT_URL => $transaction_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $fields,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded',
                'Authorization: Basic ' . base64_encode($this->api_key . ":"),
                'Accept: application/json'
            ),
        ));

        $response = curl_exec($curl);
        Log::debug($response);
        curl_close($curl);

        return json_decode($response);
    }
}
