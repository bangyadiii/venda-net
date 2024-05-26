<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use RouterOS\Client;
use RouterOS\Query;

class Router extends Model
{
    use HasFactory;

    protected $fillable = [
        'host',
        'username',
        'password',
        'last_connected_at',

        'auto_isolir',
        'isolir_action',
        'isolir_profile_id'
    ];

    public ?bool $isConnected = null;
    public Collection $profiles;

    public static function getLastClient(): ?Client
    {
        $router =  Router::latest()->first();
        if (!$router) {
            return null;
        }
        return self::getClient($router->host, $router->username, $router->password);
    }

    public function setIsConnectedAttribute($value)
    {
        $this->isConnected = (bool) $value;
    }

    public function isConnected(): bool
    {
        try {
            self::getClient($this->host, $this->username, $this->password);
        } catch (\Exception $e) {
            return false;
        }
        return true;
    }

    /**
     * Connect to the MikroTik Router with the given credentials
     *
     * @param string $host
     * @param string $username
     * @param string $password
     * @return Client
     * @throws \RouterOS\Exceptions\ClientException
     * @throws \RouterOS\Exceptions\ConfigException
     * @throws \RouterOS\Exceptions\QueryException
     * @throws \RouterOS\Exceptions\ConnectException
     * @throws \RouterOS\Exceptions\BadCredentialsException
     */
    public static function getClient(string $host, string $username, string $password): Client
    {
        // Buat koneksi baru dengan informasi login
        $config = [
            'host' => $host,
            'user' => $username,
            'pass' => $password,
            'port' => 8728,
            'attempts' => 1,
            'timeout' => 2,
            'socket_timeout' => 20,
        ];

        return new Client($config);
    }

    /**
     * Get the last connected router
     */

    /**
     * Save the connection to the database
     *
     * @param string $username
     * @param string $password
     * @param string $host
     * @return Router
     */
    public static function saveConnectionToDatabase(string $username, string $password, string $host): Router
    {
        return Router::query()->updateOrCreate(
            [
                'username' => $username,
                'password' => $password,
                'host' => $host,
            ],
            [
                'last_connected_at' => now(),
            ]
        );
    }

    /**
     * Get PPP secret from the MikroTik Router
     */
    public static function getPPPSecret(Client $client)
    {
        return $client->query('/ppp/secret/print')->read();
    }

    public static function getOnlinePPP($client)
    {
        $query = new Query('/ppp/active/print');
        return $client->query($query)->read();
    }

    public static function getRouterInfo(Client $client)
    {
        $query = new Query('/system/resource/print');
        $response = $client->query($query)->read();

        if (!empty($response)) {
            return $response[0];
        }

        return [];
    }

    public static function getInterfaces(Client $client, $interfaceType = 'ether')
    {
        $query = (new Query('/interface/print'))
            ->where('type', $interfaceType);
        $response = $client->query($query)->read();

        if (!empty($response)) {
            return $response;
        }

        return [];
    }

    public static function getTrafficData(Client $client, $interface)
    {
        $query = new Query('/interface/monitor-traffic');
        $query->equal('interface', $interface);
        $query->equal('once');

        return $client->query($query)->read();
    }
}
