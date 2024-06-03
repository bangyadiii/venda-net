<?php

namespace App\Livewire\Transaction;

use App\Enums\BillStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Payment;
use App\Models\Plan;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Filters\DateRangeFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class TransactionTable extends DataTableComponent
{
    public function builder(): Builder
    {
        return Payment::query()
            ->withWhereHas('bill', fn ($query) => $query->where('bills.status', BillStatus::PAID))
            ->where('payments.status', PaymentStatus::SUCCESS)
            ->select('*');
    }


    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            Column::make("Tanggal", "payment_date")
                ->format(fn ($value) => Carbon::parse($value)
                    ->format('d/m/Y H:i'))
                ->sortable(),
            Column::make("No Pelanggan", "bill.customer.id")
                ->searchable(),
            Column::make("Nama", "bill.customer.customer_name")
                ->searchable(),
            Column::make("Telp/WA", "bill.customer.phone_number"),
            Column::make("Paket", "bill.plan.name"),
            Column::make("Tarif", "bill.plan.price")
                ->format(fn ($value) => currency($value))
                ->sortable(),
            Column::make("Tagihan", "bill.due_date")
                ->format(fn ($value) => Carbon::parse($value)->format('F Y')),
            Column::make("PPN(%)", "bill.tax_rate"),
            Column::make("Total", "amount")
                ->format(fn ($value) => currency($value))
                ->footer(function ($rows) {
                    return 'Jumlah: ' . \currency($rows->sum('amount'));
                }),
            Column::make("Metode", "method")
                ->format(fn ($value) => match ($value) {
                    PaymentMethod::CASH => '<span class="badge text-bg-success">Tunai</span>',
                    PaymentMethod::MIDTRANS => '<span class="badge text-bg-secondary">Midtrans</span>',
                })
                ->html(),
            Column::make('Action')
                ->label(
                    fn ($row, Column $column) => view('components.livewire.datatables.action-column')->with(
                        [
                            'printRoute' => \route('invoices', ['bill_id' => $row->bill_id]),
                            'deleteMethod' => 'delete(' . $row->id . ')',
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
                    $builder->where('bill.plan_id', $value);
                }),
            SelectFilter::make('Metode', 'method')
                ->options([
                    '' => 'Semua',
                    PaymentMethod::CASH->value => 'Tunai',
                    PaymentMethod::MIDTRANS->value => 'Midtrans',
                ])
                ->filter(function (Builder $builder, $value) {
                    $builder->where('method', $value);
                }),
            DateRangeFilter::make('Tanggal Pembayaran')
                ->setFilterPillValues([0 => 'minDate', 1 => 'maxDate'])
                ->filter(function (Builder $builder, $value) {
                    $builder->whereDate('payment_date', '>=', $value['minDate'])
                        ->whereDate('payment_date', '<=', $value['maxDate']);
                }),
        ];
    }
}
