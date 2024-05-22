<div class="container-xxl container-p-y">
    <div class="card">
        <div class="card-body">
            <div class="invoice-title row">
                <div class="mb-4 col-12 col-md-6">
                    <h2 class="mb-1 text-muted">{{ $invoice->seller->name }}</h2>
                    <div class="text-muted">
                        <p class="mb-1">{!! $invoice->seller->address !!}</p>
                        <p><i class="uil uil-phone"></i> {{ $invoice->seller->phone }}</p>
                    </div>
                </div>
                <div class="col-12 col-md-6 text-end">
                    <h4 class="font-size-15">{{ __('invoices::invoice.invoice') }}
                        {{ $invoice->getSerialNumber() }}
                        <span
                            class="badge bg-{{ $invoice->status == __('invoices::invoice.paid') ? 'success' : 'secondary' }} font-size-12 ms-2">{{ $invoice->status }}</span>
                    </h4>
                    @if ($invoice->status == __('invoices::invoice.paid'))
                    <h6>
                        {{ __('invoices::invoice.payment_date') }}:
                    </h6>
                    <span class="font-size-15">
                        {{ $invoice->getPaidDate() }}
                    </span>
                    @endif

                </div>

            </div>

            <hr class="my-4">

            <div class="row">
                <div class="col-sm-6">
                    <div class="text-muted">
                        <h5 class="font-size-16 mb-3">Kepada: </h5>
                        <h5 class="font-size-15 mb-2">{{ $invoice->buyer->name }}</h5>
                        <p class="mb-1">{{ $invoice->buyer->address }}</p>
                        <p>{{ $invoice->buyer->phone }}</p>
                    </div>
                </div>
                <!-- end col -->
                <div class="col-sm-6">
                    <div class="text-muted text-sm-end">
                        <div>
                            <h5 class="font-size-15 mb-1">{{ __('invoices::invoice.due') }}:</h5>
                            <p>{{ $invoice->getDueDate() }}</p>
                        </div>
                        <div class="mt-4">
                            <h5 class="font-size-15 mb-1">Periode</h5>
                            <p>{{ $invoice->dueDate->copy()->subMonth()->addDay()->format('d F') . ' - ' . $invoice->getDueDate()}}
                            </p>
                        </div>
                    </div>
                </div>
                <!-- end col -->
            </div>
            <!-- end row -->

            <div class="py-2">
                <div class="table-responsive text-nowrap">
                    <table class="table align-striped mb-0">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>{{ __('invoices::invoice.description') }}</th>
                                <th>{{ __('invoices::invoice.price') }}</th>
                                <th class="text-end" style="width: 120px">{{ __('invoices::invoice.sub_total') }}</th>
                            </tr>
                        </thead><!-- end thead -->
                        <tbody>
                            @foreach ($invoice->items as $index => $item)
                            <tr>
                                <th scope="row">{{ $index + 1 }}</th>
                                <td>
                                    <h5 class="text-truncate font-size-14 mb-1">{{ $item->title }}</h5>
                                </td>
                                <td> {{ $invoice->formatCurrency($item->price_per_unit) }}</td>
                                <td class="text-end">{{ $invoice->formatCurrency($item->sub_total_price) }}</td>
                            </tr>
                            @endforeach

                            <!-- end tr -->
                            <tr>
                                <th scope="row" colspan="3" class="border-0 text-end">
                                    {{ __('invoices::invoice.discount') }} :</th>
                                <td class="border-0 text-end">- {{ $invoice->formatCurrency($item->discount) }}</td>
                            </tr>
                            <!-- end tr -->

                            <tr>
                                <th scope="row" colspan="3" class="border-0 text-end">
                                    {{ __('invoices::invoice.tax_rate') }}</th>
                                <td class="border-0 text-end"> {{ $invoice->tax_rate}}%</td>
                            </tr>
                            <!-- end tr -->
                            <tr>
                                <th scope="row" colspan="3" class="border-0 text-end">
                                    {{ __('invoices::invoice.total_taxes') }}</th>
                                <td class="border-0 text-end">
                                    {{ $invoice->formatCurrency($invoice->total_taxes) }}
                                </td>
                            </tr>
                            <!-- end tr -->
                            <tr>
                                <th scope="row" colspan="3" class="border-0 text-end">
                                    {{ __('invoices::invoice.total_amount') }}</th>
                                <td class="border-0 text-end">
                                    <h4 class="m-0 fw-semibold"> {{ $invoice->formatCurrency($invoice->total_amount) }}
                                    </h4>
                                </td>
                            </tr>
                            <!-- end tr -->
                        </tbody><!-- end tbody -->
                    </table><!-- end table -->
                </div><!-- end table responsive -->
                <div class="d-print-none mt-4">
                    <div class="float-end">
                        <a class="btn btn-primary me-1 text-white" href="{{ $invoice->url() }}" download>
                            <i class='bx bxs-download'></i>
                            Download</a>

                        @auth
                        <a href="{{ route('transactions.index') }}" class="btn btn-secondary me-1" wire:navigate>
                            <i class='bx bx-arrow-back'></i>
                            Back</a>
                        @else
                        <a href="{{ route('payment.index', ['id' => $customer_id]) }}" class="btn btn-secondary me-1"
                            wire:navigate>
                            <i class='bx bx-arrow-back'></i>
                            Back</a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>