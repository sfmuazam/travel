<!DOCTYPE html>
<html>

<head>
    <title>Transaction Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        .header {
            background-color: #007bff;
            color: #fff;
            padding: 20px;
            text-align: center;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        .content {
            padding: 20px;
        }

        .content p {
            font-size: 16px;
            line-height: 1.5;
            margin: 10px 0;
        }

        .content h2 {
            font-size: 20px;
            margin: 20px 0 10px;
            color: #007bff;
        }

        .footer {
            background-color: #f4f4f4;
            color: #666;
            text-align: center;
            padding: 20px;
            font-size: 14px;
        }

        .footer p {
            margin: 0;
        }

        .fw-bold {
            font-weight: bold;
        }

        .fs-5 {
            font-size: 1rem;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Transaction Details</h1>
        </div>
        <div class="content">
            <p>Dear {{ $transaction->user->name ?? 'Customer' }},</p>
            <p>Thank you for your transaction. Here are the details:</p>
            <h2>Transaction Information</h2>
            <p><strong>Transaction ID:</strong> {{ $transaction->id }}</p>
            <p><strong>Package Name:</strong> {{ $transaction->travel_package->name ?? 'N/A' }}</p>
            <p><strong>Package Type:</strong> {{ $transaction->travel_package_type->name ?? 'N/A' }}</p>
            <p><strong>Quantity:</strong> {{ $transaction->qty }}</p>
            <p><strong>Total Price:</strong> Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</p>
            <br>
            <h2>Payment Information</h2>
            @php
                $website = App\Models\Website::select('invoice_bank')->first();
            @endphp
            @if ($transaction->payment_method == 'transfer')
                @if ($website->invoice_bank)
                    <div id="footer" class="mb-4">
                        <h3 class="fs-5 fw-bold">Metode Pembayaran</h3>
                        <table style="width: 100%">
                            <tr>
                                @foreach ($website->invoice_bank as $bank)
                                    <td>
                                        <p class="mt-0 mb-0 fw-bold">
                                            <strong>{{ $bank['bank'] }}
                                            </strong>:
                                        </p>
                                        <p class="mt-0 mb-0 ">a.n {{ $bank['name'] }}</p>
                                        <p class="mt-0 mb-0 ">{{ $bank['account'] }}</p>
                                    </td>
                                @endforeach
                            </tr>
                        </table>
                    </div>
                @endif
            @endif

            {{-- <p><strong>Payment Method:</strong> {{ $transaction->payment_method }}</p>
            <p><strong>Bank Name:</strong> {{ $transaction->bank_name ?? 'N/A' }}</p>
            <p><strong>Account Number:</strong> {{ $transaction->account_number ?? 'N/A' }}</p>
            <p><strong>Account Name:</strong> {{ $transaction->account_name ?? 'N/A' }}</p> --}}
            <br>
            <p>Thank you for using our service.
            <p>Best regards,</p>
            <p>{{ config('app.name') }}</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>

</html>
