<?php

namespace App\Livewire\Customer;

use App\Livewire\Forms\CustomerForm;
use App\Models\Customer;
use App\Models\Plan;
use App\Models\Router;
use App\Models\Secret;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class ShowCustomer extends Component
{
    public Collection $plans;
    public CustomerForm $form;
    public ?Customer $customer;
    public ?Router $router;

    public function mount(Customer $customer)
    {
        $this->customer = $customer->load('plan.router');
        $this->plans = Plan::with('router')->get();
        $this->router = $this->customer->plan?->router ?? null;

        $this->form->setCustomer($this->customer);
        if ($this->router) {
            try {
                $client = Router::getClient($this->router->host, $this->router->username, $this->router->password);
                $secret = Secret::getSecret($client, $this->customer->secret_id);
                $this->form->secret_password = $secret['password'];
                $this->form->secret_username = $secret['name'];
                $this->form->ppp_service = $secret['service'];
                $this->form->local_address = $secret['local-address'] ?? null;
                $this->form->remote_address = $secret['remote-address'] ?? null;
            } catch (\Throwable $th) {
                $this->dispatch('toast', title: $th->getMessage(), type: 'error');
            }
        }
    }

    public function render()
    {
        return view('livewire.customer.show-customer');
    }
}
