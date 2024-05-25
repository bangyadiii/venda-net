<?php

namespace App\Livewire;

use App\Enums\InstallmentStatus;
use App\Enums\ServiceStatus;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Customer;
use App\Models\Router;
use RouterOS\Query;

class CustomerTable extends DataTableComponent
{
    protected $model = Customer::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            Column::make('Action')
                ->label(
                    fn ($row, Column $column) => view('components.livewire.datatables.action-column')->with(
                        [
                            'editLink' => route('customers.edit', $row),
                            'deleteMethod' => 'delete(' . $row->id . ')',
                        ]
                    )
                )->html(),
            Column::make("No Pelanggan", "id")
                ->sortable()
                ->searchable(),
            Column::make("Name", "customer_name")
                ->sortable()
                ->searchable(),
            Column::make("No Telp/WA", "phone_number")
                ->sortable(),
            Column::make("Alamat", "address")
                ->sortable(),
            Column::make("Paket", "plan.name"),
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
                )->html()
                ->sortable(),
            Column::make("Tanggal Aktif", "active_date")
                ->sortable(),
            Column::make("Tanggal Isolir", "isolir_date")
                ->sortable(),
            Column::make("Username Secret", "secret_username")
                ->sortable()
                ->searchable(),
        ];
    }

    public function delete(Customer $customer)
    {
        try {
            $customer->load('plan.router');
            $router = $customer->plan->router;
            $client = Router::getClient($router->host, $router->username, $router->password);
            $query = new Query('/ppp/secret/remove');
            $query->equal('.id', $customer->secret_id);
            $response = $client->query($query)->read();

            \throw_if(!empty($response), \Exception::class, 'Failed to delete customer secret');
            $customer->delete();
            $this->dispatch('toast', title: 'Customer deleted successfully', type: 'success');
        } catch (\Throwable $th) {
            $this->dispatch('toast', title: $th->getMessage(), type: 'danger');
        }
    }
}
