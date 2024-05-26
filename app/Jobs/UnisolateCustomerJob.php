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
use RouterOS\Client;
use RouterOS\Query;

class UnisolateCustomerJob implements ShouldQueue
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
        Secret::enableSecret($client, $this->customer->secret_id, $router->isolir_action, $this->customer->secret_id);

        if ($this->customer->service_status == ServiceStatus::SUSPENDED) {
            $this->customer->update(['service_status' => ServiceStatus::ACTIVE]);
        }
    }
}
