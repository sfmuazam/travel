<!DOCTYPE html>
<html>

<head>
    <title>Payment Success</title>
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
            background-color: #28a745;
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
            color: #28a745;
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
            <h1>Payment Success</h1>
        </div>
        <div class="content">
            <p>Dear {{ $transaction->user->name ?? 'Customer' }},</p>
            <p>Your payment has been successfully processed. Here are the details:</p>
            <br>
            <p>Thank you for your purchase. We appreciate your business.</p>
            <p>Best regards,</p>
            <p>{{ config('app.name') }}</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>

</html>
