<!DOCTYPE html>
<html lang="en">

<head>
    <title>{{ $invoice->name }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .header,
        .recipient {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }

        .header .left,
        .recipient .left,
        .header .right,
        .recipient .right {
            display: flex;
            flex-direction: column;
        }

        .invoice-title {
            margin-bottom: 20px;
        }

        .invoice-title h2,
        .invoice-title h4 {
            margin: 0;
        }

        .badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 5px;
            margin-top: 10px;
        }

        .badge.paid {
            background-color: #28a745;
            color: #fff;
        }

        .badge.unpaid {
            background-color: #6c757d;
            color: #fff;
        }

        hr {
            border: 0;
            border-top: 1px solid #eee;
            margin: 20px 0;
        }

        .table-responsive {
            margin-top: 20px;
        }

        .invoice-table {
            width: 100%;
            border-collapse: collapse;
        }

        .invoice-table th,
        .invoice-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .invoice-table th {
            background-color: #f1f1f1;
        }

        .invoice-table td {
            background-color: #fff;
        }

        /* Media Queries for Mobile Devices */
        @media (max-width: 768px) {

            .header,
            .recipient {
                grid-template-columns: 1fr;
            }

            .header .right,
            .recipient .right {
                text-align: left;
            }

            .invoice-table th,
            .invoice-table td {
                padding: 10px;
                font-size: 14px;
            }

            .container {
                padding: 15px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="invoice-title">
            <div class="header">
                <div class="left">
                    <h2>{{ $invoice->seller->name }}</h2>
                    <p>{{ $invoice->seller->address }}</p>
                    <p>{{ $invoice->seller->phone }}</p>
                </div>
                <div class="right">
                    <h4>{{ __('invoices::invoice.serial') }} {{ $invoice->getSerialNumber() }}</h4>
                    <span
                        class="badge {{ $invoice->status == __('invoices::invoice.paid') ? 'paid' : 'unpaid' }}">{{ $invoice->status }}</span>
                    <p>{{ __('invoices::invoice.date') }}:<br>{{ $invoice->getDate() }}</p>
                </div>
            </div>
        </div>

        <hr>

        <div class="recipient">
            <div class="left">
                <h5>Kepada:</h5>
                <p>{{ $invoice->buyer->name }}</p>
                <p>{{ $invoice->buyer->address }}</p>
                <p>{{ $invoice->buyer->phone }}</p>
            </div>
            <div class="right">
                <p>Periode:</p>
                <p>{{ $invoice->dueDate->copy()->subMonth()->addDay()->format('d F') . ' - ' . $invoice->getDueDate()}}
                </p>
            </div>
        </div>

        <div class="table-responsive">
            <table class="invoice-table">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>{{ __('invoices::invoice.description') }}</th>
                        <th>{{ __('invoices::invoice.price') }}</th>
                        <th>{{ __('invoices::invoice.sub_total') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($invoice->items as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->title }}</td>
                        <td>{{ $invoice->formatCurrency($item->price_per_unit) }}</td>
                        <td>{{ $invoice->formatCurrency($item->sub_total_price) }}</td>
                    </tr>
                    @endforeach
                    <tr>
                        <td colspan="3">{{ __('invoices::invoice.discount') }}</td>
                        <td>- {{ $invoice->formatCurrency($item->discount) }}</td>
                    </tr>
                    <tr>
                        <td colspan="3">{{ __('invoices::invoice.tax_rate') }}</td>
                        <td>{{ $invoice->tax_rate }}%</td>
                    </tr>
                    <tr>
                        <td colspan="3">{{ __('invoices::invoice.total_taxes') }}</td>
                        <td>{{ $invoice->formatCurrency($invoice->total_taxes) }}</td>
                    </tr>
                    <tr>
                        <td colspan="3">{{ __('invoices::invoice.total_amount') }}</td>
                        <td>{{ $invoice->formatCurrency($invoice->total_amount) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>