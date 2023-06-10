<?php
namespace Lib;
use \Redis;
class Credis
{
    private static Redis $redis;

    /**
     * @return void
     */
    private static function __init__():void
    {
        self::$redis ?? self::$redis = new \Redis();
        if(!self::$redis->isConnected())
        {
            self::$redis->connect(
                host:env('REDIS_HOST','127.0.0.1'),
                port: env('REDIS_PORT', 6379)
            );
        }
    }

    /**
     * @return void
     */
    private static function close():void
    {
        self::$redis?->close();
    }

    /**
     * @param string $key
     * @return mixed
     */
    private static function check(string $key):mixed
    {
        self::__init__();
        $data =  self::$redis->get($key);
        self::close();
        return $data;
    }
    /**
     * @param string $key
     * @param int $seconds
     * @param callable $callback
     * @return mixed
     */
    public static final function remember(string $key, int $seconds, callable $callback):mixed
    {
        self::__init__();
        $data = self::check($key);
        if(!$data)
        {
            $data = $callback();
            self::$redis->set($key,  serialize($data), $seconds);
            return $data;
        }
        self::$redis->close();
        return unserialize($data);
    }

    /**
     * @param $key
     */
    public static final function forget($key):void
    {
        self::__init__();
        self::$redis->del($key);
        self::$redis->close();
    }
}