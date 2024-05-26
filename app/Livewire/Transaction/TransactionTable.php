<?php

namespace App\Livewire\Transaction;

use App\Enums\PaymentMethod;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class TransactionTable extends DataTableComponent
{
    public function builder(): Builder
    {
        return Payment::query()
            ->with(['bill'])
            ->select('*') // Eager load anything
        ;
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            Column::make("Tanggal", "payment_date")
                ->format(fn ($value) => Carbon::parse($value))
                ->sortable(),
            Column::make("No Pelanggan", "bill.customer.id"),
            Column::make("Nama", "bill.customer.customer_name"),
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
                ->sortable(),
            Column::make("Metode", "method")
                ->sortable()
                ->format(fn ($value) => match($value) {
                    PaymentMethod::CASH => '<span class="badge text-bg-success">Tunai</span>',
                    PaymentMethod::MIDTRANS => '<span class="badge text-bg-secondary">Midtrans</span>',
                })
                ->html()
                ,
            Column::make('Action')
                ->label(
                    fn ($row, Column $column) => view('components.livewire.datatables.action-column')->with(
                        [
                            'printRoute' => route('invoices', ['id' => $row->bill_id]),
                            'deleteMethod' => 'delete(' . $row->id . ')',
                        ]
                    )
                )->html(),
        ];
    }
}