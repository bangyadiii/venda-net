<?php

namespace App\Livewire\Forms;

use App\Enums\InstallmentStatus;
use App\Enums\ServiceStatus;
use App\Models\Customer;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class CustomerForm extends Form
{
    public $customer_id;
    public $customer_name;
    public $phone_number;
    public $address;
    public $plan_id;
    public $installment_status = INstallmentStatus::NOT_INSTALLED;
    public $service_status = ServiceStatus::INACTIVE;
    public $active_date;
    public $isolir_date;

    public $secret_username;
    public $secret_password;
    public $ppp_service;
    public $discount = 0;
    public $ip_type = 'ip_pool';
    public $ipTypeSelect = [
        [
            'value' => 'ip_pool',
            'label' => 'IP Pool',
        ],
        [
            'value' => 'remote_address',
            'label' => 'Remote Address',
        ]
    ];
    public $local_address;
    public $remote_address;

    public $serviceList = [
        [
            'value' => 'pppoe',
            'label' => 'PPPoE',
        ],
        [
            'value' => 'any',
            'label' => 'Any',
        ],
        [
            'value' => 'async',
            'label' => 'Async',
        ],
        [
            'value' => 'l2tp',
            'label' => 'L2TP',
        ],
        [
            'value' => 'ovpn',
            'label' => 'OVPN',
        ],

        [
            'value' => 'pptp',
            'label' => 'PPTP',
        ],
    ];

    public $serviceStatusOptions = [
        'active',
        'inactive',
        'suspended',
    ];

    public $isSameDate = true;

    public function rules()
    {
        return [
            'customer_name' => 'required|string',
            'phone_number' => ['required', 'string', Rule::unique('customers', 'phone_number')->ignore($this->customer_id)],
            'address' => 'nullable|string',
            'plan_id' => 'required|integer',
            'discount' => 'nullable|integer',
            'installment_status' => ['required', Rule::enum(InstallmentStatus::class)],
            'service_status' => ['required', Rule::enum(ServiceStatus::class)],
            'active_date' => 'nullable|required_if:installment_status,installed|date',
            'isolir_date' => 'nullable|required_if:installment_status,installed|integer|between:1,31',
            'ppp_service' => 'required|string',
            'secret_username' => 'required|string',
            'secret_password' => 'required|string',
            'local_address' => 'nullable|string|required_if:ip_type,remote_address',
            'remote_address' => 'nullable|string|required_if:ip_type,remote_address',
            'ip_type' => 'required|string|in:ip_pool,remote_address',
        ];
    }

    public array $validationAttributes = [
        'customer_name' => 'Nama',
        'phone_number' => 'Telp/WA',
        'address' => 'Alamat',
        'plan_id' => 'Paket',
        'discount' => 'Diskon',
        'installment_status' => 'Status Pemasangan',
        'service_status' => 'Status Layanan',
        'active_date' => 'Tanggal Aktif',
        'isolir_date' => 'Tanggal Isolir',
        'ppp_service' => 'Service',
        'secret_username' => 'Secret Username',
        'secret_password' => 'Secret Password',
        'local_address' => 'Local Address',
        'remote_address' => 'Remote Address',
        'ip_type' => 'IP Type',
    ];

    public function setCustomer(Customer $customer)
    {
        $this->customer_id = $customer->id;
        $this->customer_name = $customer->customer_name;
        $this->phone_number = $customer->phone_number;
        $this->address = $customer->address;
        $this->plan_id = $customer->plan_id;
        $this->installment_status = $customer->installment_status;
        $this->service_status = $customer->service_status;
        $this->active_date = $customer->active_date;
        $this->isolir_date = $customer->isolir_date;
    }
}
