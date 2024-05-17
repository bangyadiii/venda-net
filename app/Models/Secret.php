<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use RouterOS\Query;
use Sushi\Sushi;

class Secret extends Model
{
    use Sushi;

    protected $keyType = 'string';
    private Query $query;

    protected $schema = [
        'id' => 'integer',
        'name' => 'string',
        'profile' => 'string',
        'service' => 'string'
    ];

    public function __construct()
    {
        parent::__construct();
        $this->query = new Query('/ppp/secret/print');
    }


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

    private function fetchSecrets()
    {
        try {
            $client = Router::getLastClient();
            $this->query = new Query('/ppp/secret/print');
            return $client->query($this->query)->read();
        } catch (\Throwable $th) {
            return [];
        }
    }


    public static function fetchAvailableSecrets()
    {
        $customers = Customer::all();
        $secrets = static::all();
        $availableSecrets = [];
        foreach ($secrets as $secret) {
            $isAvailable = true;
            foreach ($customers as $customer) {
                if ($customer->secret_username === $secret['name']) {
                    $isAvailable = false;
                    break;
                }
            }
            if ($isAvailable) {
                $availableSecrets[] = $secret;
            }
        }
        return $availableSecrets;
    }

    public function scopeWhere($query, $column, $operator, $value)
    {
        return $this->query->where($column, $operator, $value);
    }

    public function customer()
    {
        return $this->hasOne(Customer::class, 'secret_username', 'name');
    }
}
