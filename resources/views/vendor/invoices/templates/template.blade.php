<!DOCTYPE html>
<html lang="en">

<head>
    <title>{{ $invoice->name }}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
        }

        .w-full {
            width: 100%;
        }

        .w-half {
            width: 50%;
        }

        .invoice-title {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .invoice-title h4,
        .invoice-title h2 {
            margin: 0;
        }

        .text-muted {
            color: #6c757d;
        }

        .badge {
            padding: 0.5em 1em;
            font-size: 0.875em;
            border-radius: 0.25rem;
        }

        .bg-success {
            background-color: #28a745;
            color: #fff;
        }

        .bg-secondary {
            background-color: #6c757d;
            color: #fff;
        }

        .my-4 {
            margin-top: 1.5rem !important;
            margin-bottom: 1.5rem !important;
        }

        .mb-4 {
            margin-bottom: 1.5rem !important;
        }

        .mb-3 {
            margin-bottom: 1rem !important;
        }

        .mb-2 {
            margin-bottom: 0.5rem !important;
        }

        .font-size-15 {
            font-size: 15px;
        }

        .font-size-16 {
            font-size: 16px;
        }

        .font-size-14 {
            font-size: 14px;
        }

        .fw-semibold {
            font-weight: 600;
        }

        .text-end {
            text-align: right;
        }

        .float-end {
            float: right;
        }

        .table {
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            padding: 0.75rem;
            vertical-align: top;
            border-top: 1px solid #dee2e6;
        }

        .table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid #dee2e6;
        }

        .table tbody+tbody {
            border-top: 2px solid #dee2e6;
        }

        .table-bordered {
            border: 1px solid #dee2e6;
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid #dee2e6;
        }

        .border-0 {
            border: 0 !important;
        }

        .ms-2 {
            margin-left: 0.5rem !important;
        }

        .me-1 {
            margin-right: 0.25rem !important;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="invoice-title">
            <div>
                <h2 class="mb-1 text-muted">{{ $invoice->seller->name }}</h2>
                <p class="text-muted mb-1 col-12 col-md-3">{{ $invoice->seller->address }}</p>
                <p class="text-muted"><i class="uil uil-phone me-1"></i> {{ $invoice->seller->phone }}</p>
            </div>
            <h4 class="float-end font-size-15">{{ __('invoices::invoice.serial') }}
                {{ $invoice->getSerialNumber() }}
                <span
                    class="badge bg-{{ $invoice->status == __('invoices::invoice.paid') ? 'success' : 'secondary' }} font-size-12 ms-2">{{ $invoice->status }}</span>
            </h4>
        </div>

        <hr class="my-4">

        <div style="display: flex">
            <div class="w-half">
                <div class="text-muted">
                    <h5 class="font-size-16 mb-3">Kepada: </h5>
                    <h5 class="font-size-15 mb-2">{{ $invoice->buyer->name }}</h5>
                    <p class="mb-1">{{ $invoice->buyer->address }}</p>
                    <p>{{ $invoice->buyer->phone }}</p>
                </div>
            </div>
            <div class="w-half">
                <div class="text-muted text-sm-end">
                    <div>
                        <h5 class="font-size-15 mb-1">{{ __('invoices::invoice.date') }}:</h5>
                        <p>{{ $invoice->getDate() }}</p>
                    </div>
                    <div class="mt-4">
                        <h5 class="font-size-15 mb-1">Periode</h5>
                        <p>{{ $invoice->dueDate->copy()->subMonth()->addDay()->format('d F') . ' - ' . $invoice->getDueDate()}}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="py-2">
            <div class="table-responsive text-nowrap">
                <table class="table table-bordered align-striped mb-0">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>{{ __('invoices::invoice.description') }}</th>
                            <th>{{ __('invoices::invoice.price') }}</th>
                            <th class="text-end" style="width: 120px">{{ __('invoices::invoice.sub_total') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($invoice->items as $index => $item)
                        <tr>
                            <th scope="row">{{ $index + 1 }}</th>
                            <td>
                                <h5 class="text-truncate font-size-14 mb-1">{{ $item->title }}</h5>
                            </td>
                            <td>{{ $invoice->formatCurrency($item->price_per_unit) }}</td>
                            <td class="text-end">{{ $invoice->formatCurrency($item->sub_total_price) }}</td>
                        </tr>
                        @endforeach
                        <tr>
                            <th scope="row" colspan="3" class="border-0 text-end">
                                {{ __('invoices::invoice.discount') }} :</th>
                            <td class="border-0 text-end">- {{ $invoice->formatCurrency($item->discount) }}</td>
                        </tr>
                        <tr>
                            <th scope="row" colspan="3" class="border-0 text-end">
                                {{ __('invoices::invoice.tax_rate') }}</th>
                            <td class="border-0 text-end">{{ $invoice->tax_rate }}%</td>
                        </tr>
                        <tr>
                            <th scope="row" colspan="3" class="border-0 text-end">
                                {{ __('invoices::invoice.total_taxes') }}</th>
                            <td class="border-0 text-end">{{ $invoice->formatCurrency($invoice->total_taxes) }}</td>
                        </tr>
                        <tr>
                            <th scope="row" colspan="3" class="border-0 text-end">
                                {{ __('invoices::invoice.total_amount') }}</th>
                            <td class="border-0 text-end">
                                <h4 class="m-0 fw-semibold">{{ $invoice->formatCurrency($invoice->total_amount) }}</h4>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script type="text/php">
        if (isset($pdf) && $PAGE_COUNT > 1) {
            $text = "{{ __('invoices::invoice.page') }} {PAGE_NUM} / {PAGE_COUNT}";
            $size = 10;
            $font = $fontMetrics->getFont("Verdana");
            $width = $fontMetrics->get_text_width($text, $font, $size) / 2;
            $x = ($pdf->get_width() - $width);
            $y = $pdf->get_height() - 35;
            $pdf->page_text($x, $y, $text, $font, $size);
        }
    </script>
</body>

</html>