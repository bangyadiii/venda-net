<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use RouterOS\Client;
use RouterOS\Query;
use Sushi\Sushi;

class Secret extends Model
{
    use Sushi;

    protected $keyType = 'string';
    protected static ?Client $client = null;

    protected $schema = [
        'id' => 'integer',
        'name' => 'string',
        'password' => 'string',
        'profile' => 'string',
        'service' => 'string'
    ];

    public function getRows(): array
    {
        return array_map(function ($secret) {
            return [
                'id' => $secret['.id'],
                'name' => $secret['name'],
                'password' => $secret['password'],
                'profile' => $secret['profile'],
                'service' => $secret['service'],
            ];
        }, $this->fetchSecrets());
    }

    public static function queryForClient(Client $client): Builder
    {
        static::$client = $client;

        return static::query();
    }

    private function fetchSecrets()
    {
        try {
            $query = new Query('/ppp/secret/print');
            if (!static::$client) {
                static::$client = Router::getLastClient();
            }

            return static::$client->query($query)->read();
        } catch (\Throwable $th) {
            return [];
        }
    }

    public static function deleteSecret(Client $client, string $id): bool
    {
        $query = (new Query('/ppp/secret/remove'))
            ->equal('.id', $id);
        $resp = $client->query($query)->read();
        if (empty($resp)) {
            return true;
        }

        return false;
    }

    public static function disableSecret(Client $client, string $id): bool
    {
        $query = (new Query('/ppp/secret/disable'))
            ->equal('.id', $id);
        $resp = $client->query($query)->read();
        if (empty($resp)) {
            return true;
        }

        return false;
    }

    public static function changePPPProfile(Client $client, $secretId, $profileId): bool
    {
        try {
            $setProfileQuery = (new Query('/ppp/secret/set'))
                ->equal('.id', $secretId)
                ->equal('profile', $profileId);

            $client->query($setProfileQuery)->read();
            return true;
        } catch (\Throwable $th) {
            \info('error: ' . $th->getMessage());
            return false;
        }
    }

    /**
     * Create a new PPP secret
     *
     * @param Client $client
     * @param string $username
     * @param string $pw
     * @param string $service
     * @param string $profile
     * @param string $remote
     * @param string $local
     * @param string $type
     * @return false|string
     */
    public static function addSecret(Client $client, string $username, string $pw, $service, $profile, $remote, $local, $type): false|string
    {
        $query = new Query('/ppp/secret/add');
        $query->add('=name=' . $username)
            ->add('=password=' . $pw)
            ->add('=service=' . $service)
            ->add('=profile=' . $profile);
        if ($type === 'remote_address') {
            $query->add('=remote-address=' . $remote);
            $query->add('=local-address=' . $local);
        }
        $response = $client->query($query)->read();
        if (!isset($response['after']['ret'])) {
            return false;
        }

        return $response['after']['ret'];
    }

    public static function getSecret(Client $client, string $id): array
    {
        $query = (new Query('/ppp/secret/print'))
            ->where('.id', $id);

        $res =  $client->query($query)->read();
        if(isset($res['after']['message'])) {
            throw new Exception($res['after']['message']);
        }
        return $res[0];
    }

    public static function updateSecret(Client $client, string $id, array $values): false|string
    {
        $query = (new Query('/ppp/secret/set'))
            ->equal('.id', $id);
        foreach ($values as $key => $value) {
            $query->equal($key, $value);
        }

        $resp = $client->query($query)->read();

        if (empty($resp)) {
            return true;
        }
        return false;
    }

    public static function enableSecret(Client $client, string $id, string $type, ?string $default = 'default'): bool
    {
        if (in_array($type, ['disable_secret', 'change_profile']) === false) {
            return false;
        }

        try {
            if ($type == 'disable_secret') {
                $query = (new Query('/ppp/secret/enable'))
                    ->equal('.id', $id);

                $client->query($query)->read();
            } elseif ($type == 'change_profile') {
                $query = (new Query('/ppp/secret/set'))
                    ->equal('.id', $id)
                    ->equal('profile', $default);

                $client->query($query)->read();
            }
        } catch (\Throwable $th) {
            return false;
        }

        return true;
    }
}
