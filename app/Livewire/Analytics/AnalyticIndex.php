<?php

namespace App\Livewire\Analytics;

use App\Enums\BillStatus;
use App\Enums\ServiceStatus;
use App\Models\Customer;
use App\Models\Router;
use Exception;
use Livewire\Component;

class AnalyticIndex extends Component
{
    public ?Router $router;
    public $routers;
    public $selectedRouterId;
    public $trafficData = [
        'rx' => [],
        'tx' => [],
        'timestamps' => []
    ];
    public $cpuUsage = 0;
    public $memory = [
        'free' => 0,
        'total' => 0,
    ];
    public $interfaces = [];
    public $boardName = '-';
    public $version = '-';
    public $interface = 'ether1';
    public $hasError = false;
    public $totalCustomer = 0;
    public $paymentComplete = 0;
    public $suspended = 0;
    public $secret = 0;
    public $onlineSecret = 0;

    public function mount()
    {
        $this->routers = Router::all();
        $this->router = $this->routers->first();
        $this->selectedRouterId = $this->router?->id;
        $this->totalCustomer = Customer::count();
        $this->paymentComplete = Customer::with('payments')
            ->whereHas('bills', function ($query) {
                $query->where('status', BillStatus::PAID);
            })->count();
        $this->suspended = Customer::where('service_status', ServiceStatus::SUSPENDED)->count();
    }

    protected function initializeRouter()
    {
        if (!$this->router->isConnected) {
            $this->dispatch('toast', title: 'Tidak bisa terhubung ke router', type: 'error');
            $this->hasError = true;
            return;
        }
        $this->getRouterInfo();
        $this->fetchRouterResource();
    }

    public function fetchRouterResource()
    {
        if ($this->hasError) {
            return;
        }

        try {
            $client = Router::getClient($this->router->host, $this->router->username, $this->router->password);
            $response = Router::getRouterInfo($client);
            $this->cpuUsage = (int)($response['cpu-load'] ?? 0);
            $this->memory = [
                'free' => (int)($response['free-memory'] ?? 0),
                'total' => (int)($response['total-memory'] ?? 0),
            ];
            $this->dispatch('updateCpuChart', $this->cpuUsage);
            $this->dispatch('updateMemoryChart', $this->memory);
        } catch (\Throwable $th) {
            $this->dispatch('toast', title: $th->getMessage(), type: 'error');
        }
    }

    public function fetchTrafficData()
    {
        if ($this->hasError) {
            return;
        }

        try {
            $client = Router::getClient($this->router->host, $this->router->username, $this->router->password);
            $response = Router::getTrafficData($client, $this->interface);
        } catch (\Throwable $th) {
            if (!$this->hasError) {
                $this->dispatch('toast', title: 'Failed to fetch traffic data', type: 'error');
                $this->hasError = true;
            }
            return;
        }

        if (empty($response)) {
            if (!$this->hasError) {
                $this->dispatch('toast', title: 'No response from router', type: 'error');
                $this->hasError = true;
            }
            return;
        }

        $data = [
            'rx' => (int)$response[0]['rx-bits-per-second'],
            'tx' => (int)$response[0]['tx-bits-per-second'],
            'timestamp' => now()->format('H:i:s'),
        ];
        $this->updateTrafficData($data);
        $this->hasError = false;  // Reset error flag jika berhasil
    }

    protected function updateTrafficData($data)
    {
        $this->trafficData['rx'][] = $data['rx'];
        $this->trafficData['tx'][] = $data['tx'];
        $this->trafficData['timestamps'][] = $data['timestamp'];
        if (count($this->trafficData['rx']) > 10) {
            array_shift($this->trafficData['rx']);
            array_shift($this->trafficData['tx']);
            array_shift($this->trafficData['timestamps']);
        }
        $this->dispatch('updateTrafficChart', $this->trafficData);
    }

    public function updatedSelectedRouterId()
    {
        $this->router = Router::find($this->selectedRouterId);
        $this->hasError = false;
        $this->resetResourceData();
        $this->resetTrafficData();
        $this->initializeRouter();
    }

    public function updatedInterface()
    {
        $this->resetTrafficData();
        $this->fetchTrafficData();
    }

    public function render()
    {
        if ($this->hasError) {
            return view('livewire.analytics.index');
        }

        $this->initializeRouter();

        try {
            $client = Router::getClient($this->router->host, $this->router->username, $this->router->password);
            $secrets = Router::getPPPSecret($client);
            $onlineSecrets = Router::getOnlinePPP($client);
            if (empty($secrets) && empty($onlineSecrets)) {
                throw new Exception('No secret or online secret available');
            }
            $this->secret = count($secrets);
            $this->onlineSecret = count($onlineSecrets);
        } catch (\Throwable $th) {
            $this->dispatch('toast', title: $th->getMessage(), type: 'error');
        }

        return view('livewire.analytics.index');
    }

    protected function getRouterInfo()
    {
        try {
            $client = Router::getClient($this->router->host, $this->router->username, $this->router->password);
            $response = Router::getRouterInfo($client);
            $this->boardName = $response['board-name'] ?? '-';
            $this->version = $response['version'] ?? '-';
            $response = Router::getInterfaces($client);
            $this->interfaces = array_map(fn ($interface) => $interface['name'], $response ?? []);
        } catch (\Throwable $th) {
            $this->dispatch('toast', title: $th->getMessage(), type: 'error');
        }
    }

    private function resetResourceData()
    {
        $this->cpuUsage = 0;
        $this->memory = [
            'free' => 0,
            'total' => 0,
        ];
        $this->dispatch('updateCpuChart', $this->cpuUsage);
        $this->dispatch('updateMemoryChart', $this->memory);
    }

    protected function resetTrafficData()
    {
        $this->trafficData = [
            'rx' => [],
            'tx' => [],
            'timestamps' => []
        ];
        $this->dispatch('updateTrafficChart', $this->trafficData);
    }
}
