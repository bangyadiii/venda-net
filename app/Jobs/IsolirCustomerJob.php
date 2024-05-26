<?php

namespace App\Jobs;

use App\Enums\ServiceStatus;
use App\Models\Customer;
use App\Models\Router;
use App\Models\Secret;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class IsolirCustomerJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $customer;

    public function __construct(Customer $customer)
    {
        $this->customer = $customer;
    }

    public function handle()
    {
        $router = $this->customer->loadMissing('plan.router')->plan->router;

        $client = Router::getClient($router->host, $router->username, $router->password);
        if ($router->isolir_action == 'disable_secret') {
            $disable =  Secret::disableSecret($client, $this->customer->secret_id);
            throw_if(!$disable, new \Exception('Gagal menonaktifkan secret'));
        } elseif ($router->isolir_action == 'change_profile') {
            Secret::changePPPProfile($client, $this->customer->secret_id, $router->isolir_profile_id);
        } else {
            throw new \Exception('Action tidak ditemukan');
        }

        $this->customer->update(['service_status' => ServiceStatus::SUSPENDED]);
    }
}
