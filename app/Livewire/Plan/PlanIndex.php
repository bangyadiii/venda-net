<?php

namespace App\Livewire\Plan;

use App\Models\Plan;
use Livewire\Component;

class PlanIndex extends Component
{
    public function render()
    {
        $plans = Plan::with('router')->get();

        return view('livewire.plan.plan-index', \compact('plans'));
    }

    public function delete($id)
    {
        Plan::destroy($id);
        $this->dispatch('toast', title:'Deleted from database', type: 'success');
    }
}
