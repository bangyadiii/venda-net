<?php

namespace App\Exports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CustomerExport implements FromCollection, WithMapping, WithHeadings
{
    public $customers;

    public function __construct($customers)
    {
        $this->customers = $customers;
    }

    /**
     * @param Customer $customer
     */
    public function map($customer): array
    {
        return [
            $customer->id,
            $customer->customer_name,
            $customer->phone_number,
            $customer->address,
            $customer->installment_status->value,
            $customer->service_status->value,
            $customer->active_date,
            $customer->auto_isolir ? 'Ya' : 'Tidak',
            $customer->isolir_date,
            $customer->plan->name,
        ];
    }

    public function collection()
    {
        return Customer::query()
            ->with('plan')
            ->whereIn('id', $this->customers)->get();
    }

    public function headings(): array
    {
        return [
            'Nomor Pelanggan',
            'Nama',
            'Telepon',
            'Alamat',
            'Status Pemasangan',
            'Status Layanan',
            'Tanggal Aktif',
            'Auto Isolir',
            'Tanggal Isolir',
            'Paket',
        ];
    }
}
