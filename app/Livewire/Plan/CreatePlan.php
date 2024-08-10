<?php

namespace App\Livewire\Plan;

use App\Livewire\Forms\PlanForm;
use App\Models\Plan;
use App\Models\Profile;
use App\Models\Router;
use Illuminate\Support\Collection;
use Livewire\Component;

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
            $profiles = Profile::getProfile($client, $this->form->name);
            $plan = Plan::query()->where('name', $this->form->name)->first();
            \throw_if(!empty($profiles) || $plan, new \Exception('Paket dengan nama ' . $this->form->name . ' sudah ada'));

            // add plan to router
            $id = Profile::createProfile(
                $client,
                $this->form->name,
                $this->form->speed_limit,
            );
            \throw_if(!$id, new \Exception('Failed to create profile'));
            $plan = new Plan($this->form->all());
            $plan->ppp_profile_id = $id;
            $plan->save();
        } catch (\Throwable $th) {
            $this->dispatch('toast', title: $th->getMessage(), type: 'error');
            return \redirect()->back();
        }

        $this->dispatch('toast', title: 'Data berhasil disimpan', type: 'success');
        return $this->redirectRoute('plans.index', navigate: true);
    }
}
