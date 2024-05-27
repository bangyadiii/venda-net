<?php

namespace App\Livewire\Customer;

use App\Livewire\Forms\CustomerForm;
use App\Models\Customer;
use App\Models\Plan;
use App\Models\Router;
use App\Models\Secret;
use Exception;
use Illuminate\Support\Collection;
use Livewire\Component;

class EditCustomer extends Component
{
    public Collection $plans;
    public CustomerForm $form;
    public ?Customer $customer;
    public ?Router $router;

    public function mount(Customer $customer)
    {
        $this->customer = $customer->load('plan.router');
        $this->plans = Plan::with('router')->get();
        $this->router = $this->customer->plan->router;

        $this->form->setCustomer($this->customer);
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

    public function render()
    {
        return view('livewire.customer.edit-customer');
    }


    public function store()
    {
        $this->form->validate();
        $plan = Plan::with('router')
            ->findOrFail($this->form->plan_id);

        $router = $plan->router;
        $this->customer->fill($this->form->all());

        try {
            $client = Router::getClient($router->host, $router->username, $router->password);
            $values = [
                'name' => $this->form->secret_username,
                'password' => $this->form->secret_password,
                'service' => $this->form->ppp_service,
                'profile' => $plan->ppp_profile_id,
            ];

            if ($this->form->ip_type === 'remote_address') {
                $values['remote-address'] = $this->form->remote_address;
                $values['local-address'] = $this->form->local_address;
            }

            $id = Secret::updateSecret(
                $client,
                $this->customer->secret_id,
                $values
            );
            \throw_if(!$id, new Exception('Failed to update secret'));

            $this->customer->secret_id = $id;

            $this->customer->save();
        } catch (\Throwable $th) {
            $this->dispatch('toast', title: $th->getMessage(), type: 'error');
            return redirect()->back();
        }

        $this->dispatch('toast', title: 'Customer created successfully', type: 'success');

        return $this->redirectRoute('customers.index', navigate: true);
    }
}
