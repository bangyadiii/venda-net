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

    /**
     * Connect to the MikroTik Router with the given credentials
     *
     * @param string $host
     * @param string $username
     * @param string $password
     * @return bool
     * @throws \RouterOS\Exceptions\ClientException
     * @throws \RouterOS\Exceptions\ConfigException
     * @throws \RouterOS\Exceptions\QueryException
     * @throws \RouterOS\Exceptions\ConnectException
     * @throws \RouterOS\Exceptions\BadCredentialsException
     */

    public function connectWithCredentials(string $host, string $username, string $password)
    {
        // Buat koneksi baru dengan informasi login
        $config = [
            'host' => $host,
            'user' => $username,
            'pass' => $password,
            'port' => 8728, // Port default untuk API MikroTik RouterOS
        ];

        $this->client = new Client($config);
    }

    /**
     * Attempt to connect to all available connections
     *
     * @return void
     */
    public function connectFirstAvailable(): void
    {
        $connections = Router::all();
        foreach ($connections as $connection) {
            try {
                $this->connectWithCredentials($connection->host, $connection->username, $connection->password);
                return;
            } catch (\Exception $e) {
                continue;
            }
        }
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

    /**
     * Save the connection to the database
     *
     * @param string $username
     * @param string $password
     * @param string $host
     * @return Router
     */
    private function saveConnectionToDatabase(string $username, string $password, string $host)
    {
        return Router::create([
            'username' => $username,
            'password' => $password,
            'host' => $host,
            'last_connected_at' => now(),
        ]);
    }
    public function getClient(): ?Client
    {
        return $this->client;
    }
}
