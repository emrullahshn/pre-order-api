<?php


namespace App\Service\Redis;


interface CacheServiceInterface
{
    public function get(string $key): ?string;
    public function set(string $key, string $value, int $expireTime = 300): void;
}
