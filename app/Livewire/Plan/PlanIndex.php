<?php

namespace App\Livewire\Plan;

use App\Models\Plan;
use App\Models\Router;
use Exception;
use Livewire\Component;
use RouterOS\Query;

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
            $query = new Query('/ppp/profile/remove');
            $query->equal('.id', $plan->ppp_profile_id);
            $response = $client->query($query)->read();
            if (\is_array($response) && count($response) != 0) {
                throw new Exception('Failed to delete from Mikrotik');
            }

            $plan->delete();

            $this->dispatch('toast', title: 'Deleted from database', type: 'success');
        } catch (\Throwable $th) {
            $this->dispatch('toast', title: $th->getMessage(), type: 'danger');
        }
    }
}
