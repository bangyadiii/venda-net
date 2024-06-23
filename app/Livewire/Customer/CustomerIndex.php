<?php

namespace App\Livewire\Customer;

use App\Enums\InstallmentStatus;
use App\Enums\ServiceStatus;
use App\Models\Client;
use App\Models\Customer;
use App\Models\Plan;
use App\Models\Router;
use App\Models\Secret;
use Livewire\Component;
use RouterOS\Exceptions\ConnectException;
use RouterOS\Query;

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
        $client = new Client();
        if (!$client->connect($router->host, $router->username, $router->password)) {
            $this->dispatch('toast', title: 'Router tidak terkoneksi', type: 'error');
            return;
        }
        $secrets = $client->comm('/ppp/secret/print');

        if (empty($secrets)) {
            $this->dispatch('toast', title: 'No secrets found', type: 'error');
            return;
        }

        $secretsIds = Customer::query()
            ->whereHas('plan.router', fn ($query) => $query->where('id', $routerId))
            ->get()
            ->pluck('secret_id')
            ->toArray();
        $secrets = array_filter($secrets, fn ($secret) => !in_array($secret['.id'], $secretsIds));
        $plans = Plan::query()->where('router_id', $routerId)->get();
        if ($plans->isEmpty() && empty($secrets)) {
            return $this->dispatch('toast', title: 'Silakan import paket terlebih dahulu', type: 'error');
        }

        $errorCustomers = collect();
        foreach ($secrets as $secret) {
            $plan = $plans->where('name', $secret['profile'])->first();
            if (!$plan) {
                $errorCustomers->push($secret['name']);
                return;
            }
            Customer::create([
                'plan_id' => $plan->id,
                'secret_id' => $secret['.id'],
                'customer_name' => $secret['name'],
                'installment_status' => InstallmentStatus::INSTALLED,
                'service_status' => ServiceStatus::ACTIVE,
            ]);
        };

        $this->dispatch('toast', title: 'Synced with mikrotik', type: 'success');
        if ($errorCustomers->isNotEmpty())
            $this->dispatch('toast', title: 'Gagal untuk mengimport secret: ' . $errorCustomers->implode(', '), type: 'error');

        return $this->redirectRoute('customers.index', navigate: true);
    }
}
