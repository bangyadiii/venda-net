<?php

namespace App\Livewire\Customer;

use App\Livewire\Forms\CustomerForm;
use App\Models\Customer;
use App\Models\Plan;
use App\Models\Router;
use Illuminate\Support\Collection;
use Livewire\Component;
use RouterOS\Query;

class EditCustomer extends Component
{
    public Collection $plans;
    public CustomerForm $form;
    public ?Customer $customer;

    public function mount(Customer $customer)
    {
        $this->customer = $customer->load('plan.router');
        $this->plans = Plan::with('router')->get();
        $this->form->setCustomer($this->customer);
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
        $customer = new Customer();

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
            $customer->fill($this->form->only(
                Customer::make()->getFillable()
            ));

            $customer->save();
        } catch (\Throwable $th) {
            $this->dispatch('toast', title: $th->getMessage(), type: 'danger');
            return redirect()->back();
        }

        $this->dispatch('toast', title: 'Customer created successfully', type: 'success');

        return redirect()->route('customers.index');
    }
}
