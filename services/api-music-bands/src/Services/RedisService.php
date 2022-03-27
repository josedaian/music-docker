<?php

namespace MusicBands\Services;

class RedisService
{
    const PREFIX = 'music-api';
    protected $redis;

    public function __construct($redis)
    {
        $this->redis = $redis;
    }

    public static function buildInstance(): RedisService {
        $redisConfigs = require __DIR__ . '/../../config/redis.php';
        return new RedisService(new \Predis\Client($redisConfigs['url']));
    }

    public function generateKey($value)
    {
        return self::PREFIX . ':' . $value;
    }

    public function exists($key)
    {
        return $this->redis->exists($key);
    }

    public function get($key)
    {
        return json_decode($this->redis->get($key));
    }

    public function set($key, $value)
    {
        $this->redis->set($key, json_encode($value));
    }

    public function setex($key, $value, $ttl = 3600)
    {
        $this->redis->setex($key, $ttl, json_encode($value));
    }

    public function del($key)
    {
        $this->redis->del($key);
    }

    public function remember($key, $ttl, callable $callback){
        $defaultKey = $this->generateKey($key);
        if($this->exists($defaultKey)){
            return $this->get($defaultKey);
        }

        $value = call_user_func($callback);
        $this->setex($defaultKey, $value, $ttl);
        return $this->get($defaultKey);
    }
}