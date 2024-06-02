<?php

namespace App\Livewire\Plan;

use App\Livewire\Forms\PlanForm;
use App\Models\Plan;
use App\Models\Profile;
use App\Models\Router;
use Illuminate\Support\Collection;
use Livewire\Component;
use RouterOS\Query;

class EditPlan extends Component
{
    public PlanForm $form;
    public Collection $routers;
    public ?Plan $plan;

    public function mount($id)
    {
        $this->routers = \App\Models\Router::all();
        $this->plan = Plan::findOrFail($id);
        $this->form->setPlan($this->plan);
    }

    public function store()
    {
        $this->form->validate();

        $router = Router::find($this->form->router_id);
        try {
            $client = Router::getClient($router->host, $router->username, $router->password);
            $res = Profile::updateProfile(
                $client,
                $this->plan->ppp_profile_id,
                $this->form->name,
                $this->form->speed_limit,
            );

            \throw_if(!$res, new \Exception('Failed to update profile'));

            $this->plan->fill($this->form->except('router_id'));
            $this->plan->save();
        } catch (\Throwable $th) {
            $this->dispatch('toast', title: $th->getMessage(), type: 'error');
            return \redirect()->back();
        }

        $this->dispatch('toast', title: 'Data berhasil disimpan', type: 'success');
        return $this->redirectRoute('plans.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.plan.edit-plan');
    }
}
