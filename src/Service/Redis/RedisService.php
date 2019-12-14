<?php

namespace App\Service\Redis;


use Predis\Client;

class RedisService implements RedisServiceInterface
{
    /**
     * @var Client $client
     */
    private $client;

    /**
     * RedisService constructor.
     * @param string $redisHost
     * @param string $redisPort
     */
    public function __construct(string $redisHost, string $redisPort)
    {
        $this->client = new Client([
            'host' => $redisHost,
            'port' => $redisPort,
        ]);
    }

    /**
     * @param string $key
     * @return string
     */
    public function get(string $key): string
    {
        return $this->client->get($key);
    }

    /**
     * @param string $key
     * @param string $value
     * @param int $expireTime
     */
    public function set(string $key, string $value,int $expireTime = 300): void
    {
        $this->client->set($key, $value, 'EX', $expireTime);
    }

}
