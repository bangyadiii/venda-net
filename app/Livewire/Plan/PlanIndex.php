<?php

namespace App\Livewire\Plan;

use App\Models\Client;
use App\Models\Plan;
use App\Models\Profile;
use App\Models\Router;
use Exception;
use Livewire\Component;
use RouterOS\Exceptions\ConnectException;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class PlanIndex extends Component
{
    public $routers;
    public $plans;

    public function mount()
    {
        $this->routers = Router::all();
    }

    public function render()
    {
        $this->plans = Plan::with('router')->get();
        return view('livewire.plan.plan-index');
    }

    public function syncPlan($routerId)
    {
        try {
            $router = Router::findOrFail($routerId);
            $client = new Client();

            if (!$client->connect($router->host, $router->username, $router->password)) {
                $this->dispatch('toast', title: 'Router tidak terkoneksi', type: 'error');
                return;
            }
            $profiles = $client->comm('/ppp/profile/print');
            if (empty($profiles)) {
                $this->dispatch('toast', title: 'No profile found', type: 'error');
                return;
            }
            $plans = Plan::query()->where('router_id', $routerId)->get();
            $planIds = $plans->pluck('ppp_profile_id')->toArray();
            $profiles = \array_filter($profiles, function ($profile) use ($planIds) {
                return  !in_array($profile['.id'], $planIds);
            });

            foreach ($profiles as $profile) {
                Plan::create([
                    'router_id' => $router->id,
                    'ppp_profile_id' => $profile['.id'],
                    'name' => $profile['name'],
                    'speed_limit' => $profile['rate-limit'] ?? null,
                    'price' => 0,
                ]);
            }

            $this->dispatch('toast', title: 'Berhasil import profile mikrotik', type: 'success');
        } catch (ConnectException $th) {
            $this->dispatch('toast', title: 'Tidak bisa terkoneksi ke router', type: 'error');
        } catch (\Throwable $th) {
            $this->dispatch('toast', title: $th->getMessage(), type: 'error');
        }
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
        } catch (NotFoundResourceException $th) {
            $plan->delete();
            $this->dispatch('toast', title: 'Deleted from database', type: 'success');
        } catch (ConnectException $th) {
            $this->dispatch('toast', title: 'Failed to connect to router', type: 'error');
        } catch (\Throwable $th) {
            $this->dispatch('toast', title: $th->getMessage(), type: 'error');
        }
    }
}
