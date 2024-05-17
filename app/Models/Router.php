<?php

namespace App\Models;

use GuzzleHttp\Psr7\Query;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use RouterOS\Client;

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
            'port' => 8728, // Port default untuk API MikroTik RouterOS
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

    public function getOnlinePPP($client)
    {
        $query = new Query('/ppp/active/print');
        return $client->query($query)->read();
    }
}
