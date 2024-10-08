<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use RouterOS\Client;
use RouterOS\Query;
use Sushi\Sushi;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class Profile extends Model
{
    use Sushi;
    protected $keyType = 'string';
    protected static ?Client $client = null;

    protected $schema = [
        'id' => 'integer',
        'name' => 'string',
        'local_address' => 'string',
        'remote_address' => 'string',
        'rate_limit' => 'string',
        'default' => 'string',
        'use-ipv6' => 'string',
    ];

    public static function queryForClient(Client $client): Builder
    {
        static::$client = $client;

        return static::query();
    }

    public function getRows(): array
    {
        return array_map(function ($profile) {
            return [
                'id' => $profile['.id'],
                'name' => $profile['name'],
                'local_address' => $profile['local-address'] ?? null,
                'remote_address' => $profile['remote-address'] ?? null,
                'rate_limit' => $profile['rate-limit'] ?? null,
                'default' => $profile['default'],
                'use-ipv6' => $profile['use-ipv6'] ?? 'no'
            ];
        }, $this->fetchProfiles());
    }

    private function fetchProfiles()
    {
        try {
            $query = new Query('/ppp/profile/print');
            if (!static::$client) {
                static::$client = Router::getLastClient();
            }

            return static::$client->query($query)->read();
        } catch (\Throwable $th) {
            return [];
        }
    }

    public static function getProfile(Client $client, string $id): array
    {
        $query = (new Query('/ppp/profile/print'))
            ->where('.id', $id);
        $response = $client->query($query)->read();
        if (isset($response[0]) && $response[0]['.id'] === $id) return $response[0];
        return [];
    }

    public static function getProfileByName(Client $client, $name): array
    {
        $query = (new Query('/ppp/profile/print'))
            ->where('name', $name);
        return $client->query($query)->read();
    }

    public static function createProfile(Client $client, $name, $rate_limit): false|string
    {
        try {
            $query = (new Query('/ppp/profile/add'))
                ->add('=name=' . $name)
                ->add('=rate-limit=' . $rate_limit);
            $response = $client->query($query)->read();
            return $response['after']['ret'];
        } catch (\Throwable $th) {
            \info('error', ['error' => $th->getMessage()]);
            return false;
        }
    }

    public static function updateProfile(Client $client, $id, $name, $rate_limit): false|string
    {
        try {
            $query = (new Query('/ppp/profile/set'))
                ->equal('.id', $id)
                ->equal('name', $name)
                ->equal('rate-limit', $rate_limit);
            $response = $client->query($query)->read();
            if (empty($response)) return true;

            throw new Exception($response['after']['message']);
        } catch (\Throwable $th) {
            \info('error', ['error' => $th->getMessage()]);
            return false;
        }
    }

    public static function deleteProfile(Client $client, $id): bool
    {
        try {
            $query = (new Query('/ppp/profile/remove'))
                ->equal('.id', $id);
            $response = $client->query($query)->read();
            if (\is_array($response) && !empty($response)) {
                if (\str_contains($response['after']['message'], 'no such item')) {
                    throw new NotFoundResourceException();
                }
                throw new Exception('Failed to delete from Mikrotik');
            }

            return !isset($response['after']['ret']);
        } catch (NotFoundResourceException $th) {
            throw $th;
        } catch (\Throwable $th) {
            \info('error', ['error' => $th->getMessage()]);
            return false;
        }
    }
}
