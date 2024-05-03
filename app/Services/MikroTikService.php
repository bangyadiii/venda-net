<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Packet;
use App\Models\Router;
use RouterOS\Client;
use RouterOS\Query;

class MikroTikService
{
    protected ?Client $client = null;

    public function __construct()
    {
        // Jika tidak ada dalam sesi, coba ambil dari database
        $connection = Router::first();
        if (!$connection) {
            return;
        }

        // Jika ditemukan koneksi dalam database, gunakan informasi tersebut
        $config = [
            'host' => $connection->host,
            'user' => $connection->username,
            'pass' => $connection->password,
            'port' => 8728, // Port default untuk API MikroTik RouterOS
        ];
        $this->client = new Client($config);
    }

    public function connectWithCredentials($host, $username, $password)
    {
        // Buat koneksi baru dengan informasi login
        $config = [
            'host' => $host,
            'user' => $username,
            'pass' => $password,
            'port' => 8728, // Port default untuk API MikroTik RouterOS
        ];

        $this->client = new Client($config);

        $this->saveConnectionToDatabase($username, $password, $host);
    }

    public function syncRouter()
    {
        $this->syncPPPProfile();
        $this->syncPPPSecret();
    }

    /**
     * Syncronize the PPP Profile from the MikroTik to the database
     *
     * @return void
     */
    public function syncPPPProfile()
    {
        $client = $this->getClient();
        if (!$client) {
            return;
        }

        $query = new Query('/ppp/profile/print');
        $response = $client->query($query)->read();
        foreach ($response as $packet) {
            Packet::updateOrCreate(
                ['id' => $packet['.id']],
                [
                    'id' => $packet['.id'],
                    'name' => $packet['name'],
                    'speed_limit' => $packet['rate-limit'] ?? null,
                ]
            );
        }
    }

    /**
     * Syncronize the PPP Secret from the MikroTik to the database
     *
     * @return void
     */
    public function syncPPPSecret()
    {
        $client = $this->getClient();
        if (!$client) {
            return;
        }

        $query = new Query('/ppp/secret/print');
        $query->where('service', 'pppoe');
        $secrets = $client->query($query)
            ->read();
        foreach ($secrets as $client) {
            Customer::query()->where('secret_id', $client['.id'])
                ->firstOrNew(
                    [
                        'secret_id' => $client['.id'],
                        'customer_name' => $client['name'],
                        'ppp_username' => $client['name'],
                        'ppp_password' => $client['password'],
                        'packet_id' => Packet::where('name', $client['profile'])->first()->id ?? null,
                    ]
                );
        }
    }

    private function saveConnectionToDatabase($username, $password, $host)
    {
        Customer::create([
            'username' => $username,
            'password' => $password,
            'host' => $host,
        ]);
    }
    public function getClient(): ?Client
    {
        return $this->client;
    }
}
