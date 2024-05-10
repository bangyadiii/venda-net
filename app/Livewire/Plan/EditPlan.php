<?php

namespace App\Livewire\Plan;

use App\Livewire\Forms\PlanForm;
use App\Models\Plan;
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
            $query = new Query('/ppp/profile/set');
            $query->equal('.id', $this->plan->ppp_profile_id)
                ->equal('name', $this->form->name)
                ->equal('rate-limit', $this->form->speed_limit);

            $res = $client->query($query)->read();
            if (is_array($res) && count($res) != 0) {
                throw new \Exception('Gagal mengupdate paket di mikrotik');
            }

            $this->plan->fill($this->form->except('router_id'));
            $this->plan->save();
        } catch (\Throwable $th) {
            $this->dispatch('toast', title: $th->getMessage(), type: 'danger');
            return \redirect()->back();
        }


        $this->dispatch('toast', title: 'Saved to database', type: 'success');
        return redirect()->route('plans.index');
    }

    public function render()
    {
        return view('livewire.plan.edit-plan');
    }
}
