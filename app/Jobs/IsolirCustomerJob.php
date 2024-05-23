<?php

namespace App\Jobs;

use App\Models\Customer;
use App\Models\Router;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use RouterOS\Client;
use RouterOS\Query;

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
            $this->disablePPPSecret($client, $this->customer->secret_username);
        } elseif ($router->isolir_action == 'change_profile') {
            $this->changePPPProfile($client, $this->customer->secret_username, $router->isolir_profile_id);
        }

        $this->customer->update(['service_status' => 'suspended']);
    }

    private function disablePPPSecret(Client $client, $username)
    {
        $query = (new Query('/ppp/secret/print'))
            ->where('name', $username);

        $secrets = $client->query($query)->read();
        if (!empty($secrets)) {
            $id = $secrets[0]['.id'];
            $disableQuery = (new Query('/ppp/secret/disable'))
                ->equal('.id', $id);

            $client->query($disableQuery)->read();
        }
    }

    private function changePPPProfile(Client $client, $username, $profileId)
    {
        $query = (new Query('/ppp/secret/print'))
            ->where('name', $username);

        $secrets = $client->query($query)->read();
        if (!empty($secrets)) {
            $id = $secrets[0]['.id'];
            $setProfileQuery = (new Query('/ppp/secret/set'))
                ->equal('.id', $id)
                ->equal('profile', $profileId);

            $client->query($setProfileQuery)->read();
        }
    }
}
