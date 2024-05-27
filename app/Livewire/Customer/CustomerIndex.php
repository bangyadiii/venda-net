<?php

namespace App\Livewire\Customer;

use App\Enums\InstallmentStatus;
use App\Enums\ServiceStatus;
use App\Models\Customer;
use App\Models\Plan;
use App\Models\Router;
use App\Models\Secret;
use Livewire\Component;

class CustomerIndex extends Component
{
    public function render()
    {
        $routers = Router::all();
        return view('livewire.customer.customer-index', compact('routers'));
    }

    public function syncSecret($routerId)
    {
        $router = Router::findOrFail($routerId);
        $client = Router::getClient($router->host, $router->username, $router->password);
        $secrets = Secret::queryForClient($client)->get();
        if ($secrets->isEmpty()) {
            return $this->dispatch('toast', title: 'No secrets found', type: 'error');
        }

        $customerIds = Customer::query()
            ->whereHas('plan.router', fn ($query) => $query->where('id', $routerId))
            ->get()
            ->pluck('secret_id')
            ->toArray();

        $secrets = $secrets->filter(fn ($secret) => !in_array($secret->id, $customerIds));
        $plans = Plan::query()->where('router_id', $routerId)->get();
        if ($plans->isEmpty() && $secrets->isNotEmpty()) {
            return $this->dispatch('toast', title: 'Silakan sinkron paket terlebih dahulu', type: 'error');
        }
        $errorCustomers = collect();
        $secrets->each(function ($secret) use ($plans, $errorCustomers) {
            $plan = $plans->where('name', $secret->profile)->first();
            if (!$plan) {
                $errorCustomers->push($secret->name);
                return;
            }
            Customer::create([
                'plan_id' => $plan->id,
                'secret_id' => $secret->id,
                'customer_name' => $secret->name,
                'phone_number' => $secret->phone_number,
                'address' => $secret->address,
                'installment_status' => InstallmentStatus::INSTALLED,
                'service_status' => ServiceStatus::ACTIVE,
            ]);
        });

        $this->dispatch('toast', title: 'Synced with mikrotik', type: 'success');
        if ($errorCustomers->isNotEmpty())
            $this->dispatch('toast', title: 'Failed to sync customers: ' . $errorCustomers->implode(', '), type: 'error');

        return $this->redirectRoute('customers.index', navigate: true);
    }
}
