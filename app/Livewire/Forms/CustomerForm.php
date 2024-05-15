<?php

namespace App\Livewire\Forms;

use App\Models\Customer;
use Livewire\Attributes\Validate;
use Livewire\Form;

class CustomerForm extends Form
{
    public $customer_name;
    public $phone_number;
    public $address;
    public $plan_id;
    public $installment_status = 'not_installed';
    public $service_status = 'inactive';
    public $active_date;
    public $payment_deadline;
    public $secret_type = 'add_secret';
    public $secretTypeSelect = [
        [
            'value' => 'add_secret',
            'label' => 'Tambah Secret',
        ],
        [
            'value' => 'existing_secret',
            'label' => 'Ambil Secret dari Router',
        ],
    ];

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

    public $isSameDate = true;

    public array $rules = [
        'customer_name' => 'required|string',
        'phone_number' => 'required|string|unique:customers,phone_number',
        'address' => 'nullable|string',
        'plan_id' => 'required|integer',
        'discount' => 'nullable|integer',
        'installment_status' => 'required|string|in:not_installed,installed',
        'service_status' => 'required|string|in:active,inactive',
        'active_date' => 'nullable|required_if:installment_status,installed|date',
        'payment_deadline' => 'nullable|required_if:installment_status,installed|integer|between:1,31',
        'ppp_service' => 'required|string',
        'secret_username' => 'required|string',
        'secret_password' => 'required|string',
        'secret_type' => 'required|string',
        'local_address' => 'nullable|string|required_if:ip_type,remote_address',
        'remote_address' => 'nullable|string|required_if:ip_type,remote_address',
        'ip_type' => 'required|string|in:ip_pool,remote_address',
    ];

    public function setCustomer(Customer $customer)
    {
        $this->customer_name = $customer->customer_name;
        $this->phone_number = $customer->phone_number;
        $this->address = $customer->address;
        $this->plan_id = $customer->plan_id;
        $this->installment_status = $customer->installment_status;
        $this->service_status = $customer->service_status;
        $this->active_date = $customer->active_date;
        $this->payment_deadline = $customer->payment_deadline;
        $this->secret_username = $customer->secret_username;
        $this->secret_password = $customer->secret_password;
    }
}
