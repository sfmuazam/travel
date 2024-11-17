<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $fileName }}</title>

    <link href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Nunito', sans-serif;
            padding: 20px;
            -webkit-font-smoothing: antialiased;
        }

        hr {
            margin: 1rem 0;
            color: inherit;
            border: 0;
            opacity: .25;
            border-top: 1px solid black;
        }

        .d-flex {
            display: flex;
        }

        .justify-content-between {
            justify-content: space-between;
        }

        .align-items-center {
            align-items: center;
        }

        .align-items-end {
            align-items: flex-end;
        }

        .flex-column {
            flex-direction: column;
        }

        .col-3 {
            width: 25%;
        }

        .col-6 {
            width: 50%;
        }

        .lh-sm {
            line-height: 1.25;
        }

        .fw-bold {
            font-weight: bold;
        }

        .fs-5 {
            font-size: 1.25rem;
        }

        .border-2 {
            border-width: 2px;
        }

        .border-black {
            border-color: black;
        }

        .opacity-100 {
            opacity: 1;
        }

        .border-bottom {
            border-bottom: 1px solid black;
        }

        .border-top {
            border-top: 1px solid black;
        }

        .text-center {
            text-align: center;
        }

        .text-end {
            text-align: right;
        }

        .mb-5 {
            margin-bottom: 3rem;
        }

        .mb-4 {
            margin-bottom: 1.5rem;
        }

        .mb-3 {
            margin-bottom: 1rem;
        }

        .mb-2 {
            margin-bottom: 3px;
        }

        .mt-0 {
            margin-top: 0;
        }

        .pt-3 {
            padding-top: 1rem;
        }

        .p-0 {
            padding: 0;
        }

        .m-0 {
            margin: 0;
        }

        .mb-0 {
            margin-bottom: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 8px;
        }



        th {
            text-align: left;
        }

        h3 {
            margin-block: 0;
        }
    </style>
</head>

<body class="antialiased px-5 py-4">
    <table style="width: 100%">
        <tr>
            <td style="text-align: left;">
                <h2 style="margin: -2px;">Invoice</h2>
            </td>
            @if ($imageSrc)
                <td style="text-align: right;">
                    <img src="{{ $imageSrc }}" alt="gambar" width="80" height="80" />
                </td>
            @endif
        </tr>
    </table>
    <div id="invoice" class="mb-2">
        <h3 class="fs-5 fw-bold">DETAIL INVOICE</h3>
        <hr class="border-2 border-black opacity-100 mt-0 mb-2">
        <table style="width: 100%">
            <tr>
                <td>
                    <p class="p-0 m-0">Invoice ID: {{ $invoice->invoice_id }}</p>
                    <p class="p-0 m-0">Invoice Created:
                        {{ \Carbon\Carbon::parse($invoice->created_at)->format('j M Y H:i') }}</p>
                    </p>
                    <p class="p-0 m-0">Due Date: {{ \Carbon\Carbon::parse($invoice->expires_at)->format('j M Y H:i') }}
                    </p>
                </td>
                <td class="text-end">
                    <p class="p-0 m-0">{{ $website->inv_title ? $website->inv_title : 'Invoice' }}</p>
                    <p class="p-0 m-0">{{ $website->inv_phone ? $website->inv_phone : '' }} -
                        {{ $website->inv_email ? $website->inv_email : '' }}</p>
                </td>
            </tr>
        </table>
    </div>

    <div id="packages" class="mb-2">
        <h3 class="fs-5 fw-bold">DETAIL PAKET</h3>
        <hr class="border-2 border-black opacity-100 mt-0 mb-2">

        <table style="width: 100%">
            <tr>
                <td>
                    <p class="p-0 m-0">Paket: {{ $invoice->transaction->travel_package->name }}</p>
                    <p class="p-0 m-0">Keberangkatan:
                        {{ \Carbon\Carbon::parse($invoice->transaction->travel_package->departure_date)->format('j M Y') }}
                    </p>
                </td>
                <td class="text-end">
                    @if ($invoice->transaction->travel_package->airline)
                        <p class="p-0 m-0">Airline: Garuda</p>
                    @endif
                    @if ($invoice->transaction->travel_package->catering)
                        <p class="p-0 m-0">Catering: Bu Ijah</p>
                    @endif
                    @if ($invoice->transaction->travel_package->hotel)
                        <p class="p-0 m-0">Hotel: </p>
                    @endif
                    @if ($invoice->transaction->travel_package->transportation)
                        <p class="p-0 m-0">Transportation: Bus</p>
                    @endif
                </td>
            </tr>

        </table>
    </div>

    <div id="total" class="mb-2">
        <h3 class="fs-5 fw-bold">PESANAN</h3>
        <hr class="border-2 border-black opacity-100 mt-0 mb-2">
        <table>
            <thead>
                <tr>
                    <th class="col-3">NAME</th>
                    <th class="text-center col-6">VARIANT</th>
                    <th class="text-end">HARGA</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($invoice->transaction->customer as $customer)
                    <tr>
                        <td class="col-3">{{ $customer->manifest->name }}</td>
                        <td class="text-center col-6">{{ $invoice->transaction->travel_package_type->name }}</td>
                        <td class="text-end">
                            {{ 'Rp' . number_format($invoice->transaction->travel_package_type->discount_price, 0, ',', '.') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @php
            $transaction = $invoice->transaction;
            $addons = App\Models\TransactionAddons::where('transaction_id', $transaction->id)->get();
        @endphp
        @if ($addons)
            <table>
                <thead class="border-black border-bottom opacity-100">
                    <tr>
                        <th>ADDON</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($addons as $addon)
                    @php
                        // dd($addon);
                       $dataaddons = App\Models\AddOnProduct::find($addon->addons_id);
                    @endphp
                        <tr>
                            <td>
                                <p class="fw-bold m-0 p-0">
                                    {{ $dataaddons->title }}
                                </p>
                                <p class="m-0 p-0">{{ $dataaddons->description }}</p>
                            </td>
                            <td colspan="2" class="text-end">
                                {{ 'Rp' . number_format($dataaddons->discount_price, 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        <table>
            <tr class="border-top opacity-100">
                <td></td>
                <td></td>
                <td class="fw-bold text-end">Total:</td>
                <td class="text-end">
                    {{ 'Rp' . number_format($invoice->transaction->total_price, 0, ',', '.') }}
                </td>
            </tr>
            @if ($invoice->transaction->payment_status == 'partial')
                <tr class="border-top opacity-100">
                    <td></td>
                    <td></td>
                    <td class="fw-bold text-end">DP Minimal:</td>
                    <td class="text-end">@php
                        $total = $invoice->transaction->total_price;
                        $dp_percen = App\Models\PaymentSettings::find(1)->dp_percentage;
                        $dp = ($total * $dp_percen) / 100;
                        echo 'Rp' . number_format($dp, 0, ',', '.');
                    @endphp</td>
                </tr>
            @endif

        </table>
    </div>

    @if ($website->invoice_bank)
        <div id="footer" class="mb-4">
            <h3 class="fs-5 fw-bold">Metode Pembayaran</h3>
            <table style="width: 100%">
                <tr>
                    @foreach ($website->invoice_bank as $bank)
                        <td>
                            <p class="mt-0 mb-0 fw-bold">{{ $bank['bank'] }}:</p>
                            <p class="mt-0 mb-0 ">a.n {{ $bank['name'] }}</p>
                            <p class="mt-0 mb-0 ">{{ $bank['account'] }}</p>
                        </td>
                    @endforeach
                </tr>
            </table>
        </div>
    @endif


    <p>Thank you for your order.</p>

</body>

</html>
