<?php

namespace App\Livewire\Transaction;

use App\Enums\BillStatus;
use App\Enums\PaymentMethod;
use App\Models\Bill;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Plan;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Filters\DateFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\DateRangeFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class TransactionTable extends DataTableComponent
{
    protected $model = Bill::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setDefaultSort('due_date', 'desc');
    }

    public function columns(): array
    {
        return [
            Column::make("No Pelanggan", "customer.id")
                ->searchable(),
            Column::make("Nama", "customer.customer_name")
                ->searchable(),
            Column::make("Paket", "plan.name")
                ->sortable(),
            Column::make("Tarif", "plan.price")
                ->format(fn ($value) => currency($value))
                ->sortable(),
            Column::make("Tagihan", "due_date")
                ->format(fn ($value) => Carbon::parse($value)->format('F Y'))
                ->sortable(),
            Column::make("Diskon", "discount")
                ->format(fn ($value) => currency($value))
                ->sortable(),
            Column::make("PPN(%)", "tax_rate")
                ->sortable(),
            Column::make("Total", "total_amount")
                ->format(fn ($value) => currency($value))
                ->sortable()
                ->footer(function ($rows) {
                    return 'Jumlah: ' . \currency($rows->sum('total_amount'));
                }),
            Column::make("Status", "status")
                ->format(function ($value, $bill) {
                    if ($value == BillStatus::PAID) {
                        return '<span class="badge text-bg-success">LUNAS</span>';
                    }
                    if ($value == BillStatus::UNPAID && $bill['payment.method'] == 'midtrans') {
                        return '<span class="badge text-bg-secondary">Pending</span>';
                    } else {
                        return '<span class="badge text-bg-danger">BELUM LUNAS</span>';
                    }
                })
                ->html(),
            Column::make("Tanggal Pembayaran", "payment.payment_date")
                ->format(fn ($value) => $value ? Carbon::parse($value)->format('d M Y H:i:s') : '-')
                ->sortable(),
            Column::make("Metode", "payment.method")
                ->format(function ($value) {
                    if (!$value) {
                        return '-';
                    }

                    if ($value === PaymentMethod::CASH->value) {
                        return '<span class="badge text-bg-primary">TUNAI</span>';
                    } else {
                        return '<span class="badge text-bg-warning">MIDTRANS</span>';
                    }
                })

                ->html(),
            Column::make('Action', 'id')
                ->label(
                    fn ($row, Column $column) => view('components.livewire.datatables.action-column')->with(
                        [
                            'printRoute' => route('invoices', ['id' => $row->id]),
                            // 'deleteMethod' => 'delete(' . $row->id . ')',
                        ]
                    )
                )->html(),
        ];
    }

    public function filters(): array
    {
        $paket = Plan::pluck('name', 'id')->toArray();
        array_unshift($paket, 'Semua');

        return [
            SelectFilter::make('Paket')
                ->options($paket)
                ->filter(function (Builder $builder, $value) {
                    $builder->where('bills.plan_id', $value);
                }),
            DateFilter::make('Tagihan')
                ->config([
                    'pillFormat' => 'M Y',
                ])
                ->filter(function (Builder $builder, $value) {
                    $date = Carbon::parse($value);
                    $builder->whereMonth('payment_date', $date->month)
                        ->whereYear('payment_date', $date->year);
                }),
            SelectFilter::make('Status', 'status')
                ->options([
                    '' => 'Semua',
                    BillStatus::PAID->value => 'Lunas',
                    BillStatus::UNPAID->value => 'Belum Dibayar',
                    'pending' => 'Pembayaran Belum Selesai',
                ])
                ->filter(function (Builder $builder, $value) {
                    if ($value == 'pending') {
                        $builder->where('bills.status', BillStatus::UNPAID)
                            ->where('payment.method', PaymentMethod::MIDTRANS);
                        return;
                    }
                    $builder->where('bills.status', $value);
                }),
            SelectFilter::make('Metode', 'method')
                ->options([
                    '' => 'Semua',
                    PaymentMethod::CASH->value => 'Tunai',
                    PaymentMethod::MIDTRANS->value => 'Midtrans',
                ])
                ->filter(function (Builder $builder, $value) {
                    $builder->withWhereHas('payment', function ($query) use ($value) {
                        $query->where('method', $value);
                    });
                }),
            DateRangeFilter::make('Tanggal Pembayaran')
                ->setFilterPillValues([0 => 'minDate', 1 => 'maxDate'])
                ->filter(function (Builder $builder, $value) {
                    $builder->withWhereHas('payment', function ($query) use ($value) {
                        $query->whereDate('payment_date', '>=', $value['minDate'])
                            ->whereDate('payment_date', '<=', $value['maxDate']);
                    });
                }),
        ];
    }
}
