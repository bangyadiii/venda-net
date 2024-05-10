<?php

namespace App\Livewire\Plan;

use App\Livewire\Forms\PlanForm;
use App\Models\Plan;
use App\Models\Router;
use Illuminate\Support\Collection;
use Livewire\Component;
use RouterOS\Query;

class CreatePlan extends Component
{
    public PlanForm $form;
    public Collection $routers;

    public function mount()
    {
        $this->routers = \App\Models\Router::all();
    }

    public function render()
    {
        return view('livewire.plan.create-plan');
    }

    public function store()
    {
        $this->form->validate();

        $router = Router::find($this->form->router_id);
        try {
            $client = Router::getClient($router->host, $router->username, $router->password);
            // check if the ppp profile is exist on mikrotik or not
            $query = new Query('/ppp/profile/print');
            $query->where('name', $this->form->name);
            $profile = $client->query($query)->read();
            $plan = Plan::query()->where('name', $this->form->name)->first();
            if (count($profile) > 0 || $plan) {
                $this->dispatch('toast', title: 'Paket dengan nama ' . $this->form->name . 'sudah ada', type: 'danger');
                return \redirect()->back();
            }

            // add plan to router
            $query = new Query('/ppp/profile/add');
            $response = $client->query(
                $query->add('=name=' . $this->form->name)
                    ->add('=rate-limit=' . $this->form->speed_limit . 'M')
                    ->add('=local-address=pool_PPPoE')
                    ->add('=remote-address=pool_PPPoE')
            )->read();
            $plan = new Plan($this->form->all());
            $plan->ppp_profile_id = $response['after']['ret'];
            $plan->save();
        } catch (\Throwable $th) {
            $this->dispatch('toast', title: 'Tidak bisa terhubung dengan Mikrotik', type: 'danger');
            return \redirect()->back();
        }


        $this->dispatch('toast', title: 'Saved to database', type: 'success');
        return redirect()->route('plans.index');
    }
}
