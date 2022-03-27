<?php

namespace MusicBands\Services;

use MusicBands\Exceptions\PublicException;

class RedisService
{
    const PREFIX = 'music-api';
    private const TYPE_CLASS = 100;
    private const TYPE_ARRAY = 200;
    private const TYPE_STRING = 300;
    protected $redis;

    public function __construct($redis)
    {
        $this->redis = $redis;
    }

    public static function buildInstance(): RedisService {
        $redisConfigs = require __DIR__ . '/../../config/redis.php';
        return new RedisService(new \Predis\Client($redisConfigs['url']));
    }

    private function generateKey($value)
    {
        return self::PREFIX . ':' . $value;
    }

    public function exists($key)
    {
        return $this->redis->exists($this->generateKey($key));
    }

    public function get($key)
    {
        return $this->readableValue($this->redis->get($this->generateKey($key)));
    }

    public function set($key, $value)
    {
        $this->redis->set($this->generateKey($key), $this->writableValue($value));
    }

    public function setex($key, $value, $ttl = 3600)
    {
        $this->redis->setex($this->generateKey($key), $ttl, $this->writableValue($value));
    }

    public function del($key)
    {
        $this->redis->del($this->generateKey($key));
    }

    public function remember($key, $ttl, callable $callback){
        if($this->exists($key)){
            return $this->get($key);
        }

        $value = call_user_func($callback);
        $this->setex($key, $value, $ttl);
        return $this->get($key);
    }

    private function writableValue($value){
        if(is_object($value) && get_class($value) != false){
            $data = [
                'value' => serialize($value),
                'type' => self::TYPE_CLASS
            ];
        }elseif (is_array($value)){
            $data = [
                'value' => json_encode($value),
                'type' => self::TYPE_ARRAY
            ];
        }else{
            $data = [
                'value' => $value,
                'type' => self::TYPE_STRING
            ];
        }

        return json_encode($data);
    }

    /**
     * @param $value
     * @return mixed|null
     * @throws PublicException
     */
    private function readableValue($value){
        if(null === $value) return null;
        $data = json_decode($value, true);
        switch ($data['type']){
            case self::TYPE_CLASS:
                return unserialize($data['value']);
            case self::TYPE_ARRAY:
                return json_decode($data['value']);
            case self::TYPE_STRING:
                return $data['value'];
            default:
                throw PublicException::internalError(sprintf('El tipo %s no está soportado dentro de %s', $data['type'], __CLASS__), 'bad_type.redis');
        }
    }
}