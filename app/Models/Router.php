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

        'isolir_action',
        'isolir_profile_id'
    ];

    public Collection $profiles;

    // relations
    public function plans()
    {
        return $this->hasMany(Plan::class);
    }

    public function customers()
    {
        return $this->hasManyThrough(Customer::class, Plan::class);
    }

    public static function getLastClient(): ?Client
    {
        $router =  Router::latest()->first();
        if (!$router) {
            return null;
        }
        return self::getClient($router->host, $router->username, $router->password);
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
            'port' => (int) config('routeros-api.port', 8729),
            'attempts' => 1,
            'timeout' => 10,
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
            ]
        );
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
