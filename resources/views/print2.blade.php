<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Invoice</title>

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
    @php
        $invoice = App\Models\Invoice::find(2);
        // dd($invoice->transaction->customer[0]->manifest);
        // dd($invoice->transaction->travel_package_type);
    @endphp
       @foreach ($invoice->transaction->addons as $addon)
        @php
            dd($addon);
            // dd($addon['addons_name']);
        @endphp
       @endforeach
    <table style="width: 100%">
        <tr>
            <td style="display: flex">
                <h2 style="margin: -2px;">Invoice</h2>

            </td>
            <td class="text-end">
                <img
                   width="100" height="100" src="https://raw.githubusercontent.com/laravel/art/d5f5e725c27f877ed032225fe0b00afee9337d0f/laravel-logo.png" />
            </td>
        </tr>
    </table>
    <div id="invoice" class="mb-2">
        <h3 class="fs-5 fw-bold">DETAIL INVOICE</h3>
        <hr class="border-2 border-black opacity-100 mt-0 mb-2">
        <table style="width: 100%">
            <tr>
                <td>
                    <p class="p-0 m-0">Invoice ID: INV-10001001</p>
                    <p class="p-0 m-0">Invoice Created: 10-10-2022 14:00</p>
                    <p class="p-0 m-0">Due Date: 10-10-2022 17:00</p>
                </td>
                <td class="text-end">
                    <p class="p-0 m-0">PT Digi Kreatif</p>
                    <p class="p-0 m-0">089123129 - cs@gmail.com</p>
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
                    <p class="p-0 m-0">Paket: Diamond Star</p>
                    <p class="p-0 m-0">Keberangkatan: 10-10-2022</p>
                </td>
                <td class="text-end">
                    <p class="p-0 m-0">Hotel: Aston</p>
                    <p class="p-0 m-0">Catering: Bu Ijah</p>
                    <p class="p-0 m-0">Airline: Garuda</p>
                    <p class="p-0 m-0">Transportation: Bus</p>
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
                <tr>
                    <td class="col-3">John Doe</td>
                    <td class="text-center col-6">Standard</td>
                    <td class="text-end">Rp 1.000.000</td>
                </tr>
            </tbody>
        </table>
        <table>
            <thead >
                <tr class="border-black border-bottom opacity-100">
                    <th>ADDON</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="2">
                        <p class="fw-bold m-0 p-0">
                            PERLENGKAPAN JAMAAH MP
                        </p>
                        <p class="m-0 p-0">Hahah ashdhasdahsdaF</p>
                    </td>
                    <td colspan="2" class="text-end">IDR 1.500.000
                    </td>
                </tr>
                
            </tbody>
        </table>
        <table>
            <tr class="border-top opacity-100">
                <td></td>
                <td></td>
                <td class="fw-bold text-end">Total:</td>
                <td class="text-end">IDR 33.100.000</td>
            </tr>
            <tr class="border-top opacity-100">
                <td></td>
                <td></td>
                <td class="fw-bold text-end">DP Minimal:</td>
                <td class="text-end">IDR 10.100.000</td>
            </tr>
        </table>
    </div>

    <div id="footer" class="mb-4">
        <h3 class="fs-5 fw-bold">Metode Pembayaran</h3>
        <table style="width: 100%">
            <tr>
                <td>
                    <p class="mt-0 mb-0 fw-bold">BNI:</p>
                    <p class="mt-0 mb-0 ">a.n Digi Kreatif</p>
                    <p class="mt-0 mb-0 ">0099213812</p>
                </td>
                <td>
                    <p class="mt-0 mb-0 fw-bold">BNI:</p>
                    <p class="mt-0 mb-0 ">a.n Digi Kreatif</p>
                    <p class="mt-0 mb-0 ">0099213812</p>
                </td>
                <td>
                    <p class="mt-0 mb-0 fw-bold">BNI:</p>
                    <p class="mt-0 mb-0 ">a.n Digi Kreatif</p>
                    <p class="mt-0 mb-0 ">0099213812</p>
                </td>
                <td>
                    <p class="mt-0 mb-0 fw-bold">BNI:</p>
                    <p class="mt-0 mb-0 ">a.n Digi Kreatif</p>
                    <p class="mt-0 mb-0 ">0099213812</p>
                </td>
            </tr>
        </table>
    </div>

    <p>Thank you for your order.</p>

</body>

</html>
