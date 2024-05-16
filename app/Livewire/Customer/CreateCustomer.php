<?php

namespace App\Livewire\Customer;

use App\Livewire\Forms\CustomerForm;
use App\Models\Customer;
use App\Models\Plan;
use App\Models\Router;
use Illuminate\Support\Collection;
use Livewire\Component;
use RouterOS\Query;

class CreateCustomer extends Component
{
    public Collection $plans;
    public CustomerForm $form;

    public function mount()
    {
        $this->plans = Plan::with('router')->get();
    }

    public function render()
    {
        return view('livewire.customer.create-customer');
    }

    public function store()
    {
        $this->form->validate();
        $plan = Plan::with('router')
            ->findOrFail($this->form->plan_id);

        $router = $plan->router;
        $customer = new Customer();
        if ($this->form->secret_type === 'add_secret') {
            try {
                $client = Router::getClient($router->host, $router->username, $router->password);
                $query = new Query('/ppp/secret/add');
                $query->add('=name=' . $this->form->secret_username)
                    ->add('=password=' . $this->form->secret_password)
                    ->add('=service=' . $this->form->ppp_service)
                    ->add('=profile=' . $plan->ppp_profile_id);
                if ($this->form->ip_type === 'remote_address') {
                    $query->add('=remote-address=' . $this->form->remote_address);
                    $query->add('=local-address=' . $this->form->local_address);
                }
                $response = $client->query($query)->read();
                if (!isset($response['after']['ret'])) {
                    throw new \Exception($response['after']['message'] ?? 'Failed to create customer');
                }
            } catch (\Throwable $th) {
                $this->dispatch('toast', title: $th->getMessage(), type: 'danger');
                return redirect()->back();
            }
        }

        $customer->fill($this->form->only(
            Customer::make()->getFillable()
        ));

        $customer->save();
        $this->dispatch('toast', title: 'Customer created successfully', type: 'success');

        return redirect()->route('customers.index');
    }
}
