<?php

namespace App\Livewire;

use App\Enums\InstallmentStatus;
use App\Enums\ServiceStatus;
use App\Exports\CustomerExport;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Customer;
use App\Models\Plan;
use App\Models\Router;
use App\Models\Secret;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Facades\Excel;
use Rappasoft\LaravelLivewireTables\Views\Filters\DateFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\DateRangeFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class CustomerTable extends DataTableComponent
{
    protected $model = Customer::class;
    public $secrets;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function bulkActions(): array
    {
        return [
            'export' => 'Export',
        ];
    }

    public function export()
    {
        $customers = $this->getSelected();

        $this->clearSelected();

        return Excel::download(new CustomerExport($customers), 'customers.xlsx');
    }

    public function columns(): array
    {
        return [
            Column::make('Action')
                ->label(
                    fn ($row, Column $column) => view('components.livewire.datatables.action-column')->with(
                        [
                            'showLink' => route('customers.show', $row),
                            'editLink' => route('customers.edit', $row),
                            'deleteMethod' => 'delete(' . $row->id . ')',
                        ]
                    )
                )->html(),
            Column::make("No Pelanggan", "id")
                ->searchable(),
            Column::make("Name", "customer_name")
                ->sortable()
                ->searchable(),
            Column::make("No Telp/WA", "phone_number")
                ->searchable(),
            Column::make("Alamat", "address")
                ->searchable(),
            Column::make("Paket", "plan.name")
                ->format(fn ($value) => $value ?? '-'),
            Column::make("Status Pemasangan", "installment_status")
                ->format(
                    fn ($value) => match ($value) {
                        InstallmentStatus::INSTALLED => '<span class="badge text-bg-success">Terpasang</span>',
                        InstallmentStatus::NOT_INSTALLED => '<span class="badge text-bg-secondary">Belum Terpasang</span>',
                        default => 'Tidak Diketahui',
                    }
                )->html(),
            Column::make("Status Layanan", "service_status")
                ->format(
                    fn ($status) => match ($status) {
                        ServiceStatus::ACTIVE => '<span class="badge text-bg-success">Aktif</span>',
                        ServiceStatus::INACTIVE => '<span class="badge text-bg-secondary">Belum Aktif</span>',
                        ServiceStatus::SUSPENDED => '<span class="badge text-bg-danger">Suspend</span>',
                        default => 'Tidak Diketahui',
                    }
                )->html(),
            Column::make("Tanggal Aktif", "active_date")
                ->format(fn ($value) => Carbon::parse($value)->format('d/m/Y'))
                ->sortable(),
            Column::make("Tanggal Isolir", "isolir_date")
                ->format(function ($value) {
                    if($value == 'last_day'){
                        return 'Akhir Bulan';
                    }
                    return $value;
                })
                ->sortable(),

            // Column::make("IP Remote ", "remote_address")
            //     ->format(fn ($value) => '<a href="' . $value . '" target="_blank">' . $value . '</a>' ?? '-'),
        ];
    }

    public function delete(Customer $customer)
    {
        try {
            $customer->load('plan.router');
            $router = $customer->plan->router;
            $client = Router::getClient($router->host, $router->username, $router->password);
            $deleted = Secret::deleteSecret($client, $customer->secret_id);

            \throw_if(!$deleted, \Exception::class, 'Failed to delete customer secret');
            $customer->delete();
            $this->dispatch('toast', title: 'Customer deleted successfully', type: 'success');
        } catch (\Throwable $th) {
            $this->dispatch('toast', title: $th->getMessage(), type: 'error');
        }
    }

    public function filters(): array
    {
        $paket = Plan::pluck('name', 'id')->toArray();
        array_unshift($paket, 'Semua');

        return [
            SelectFilter::make('Paket')
                ->options($paket)
                ->filter(function (Builder $builder, $value) {
                    $builder->where('plan_id', $value);
                }),
            SelectFilter::make('Status Pemasangan')
                ->options([
                    '' => 'Semua',
                    InstallmentStatus::INSTALLED->value => 'Terpasang',
                    InstallmentStatus::NOT_INSTALLED->value => 'Belum Terpasang',
                ])
                ->filter(function (Builder $builder, $value) {
                    $builder->where('installment_status', $value);
                }),
            SelectFilter::make('Status Layanan')
                ->options([
                    '' => 'Semua',
                    ServiceStatus::ACTIVE->value => 'Aktif',
                    ServiceStatus::INACTIVE->value => 'Non Aktif',
                    ServiceStatus::SUSPENDED->value => 'Suspended',
                ])
                ->filter(function (Builder $builder, $value) {
                    $builder->where('service_status', $value);
                }),
            DateRangeFilter::make('Tanggal Aktif')
                ->setFilterPillValues([0 => 'minDate', 1 => 'maxDate'])
                ->filter(function (Builder $builder, $dateRange) {
                    $builder->whereDate('active_date', '>=', $dateRange['minDate'])
                        ->whereDate('active_date', '<=', $dateRange['maxDate']);
                }),
        ];
    }
}
