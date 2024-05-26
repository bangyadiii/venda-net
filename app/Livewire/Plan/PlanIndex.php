<?php

namespace App\Livewire\Plan;

use App\Models\Plan;
use App\Models\Profile;
use App\Models\Router;
use Exception;
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
        $plan = Plan::query()->with('router')->findOrFail($id);
        $router = $plan->router;
        try {
            $client = Router::getClient($router->host, $router->username, $router->password);
            $deleted = Profile::deleteProfile($client, $plan->ppp_profile_id);
            \throw_if(!$deleted, new Exception('Failed to delete profile from mikrotik'));
            $plan->delete();

            $this->dispatch('toast', title: 'Deleted from database', type: 'success');
        } catch (\Throwable $th) {
            $this->dispatch('toast', title: $th->getMessage(), type: 'error');
        }
    }
}
