<?php

namespace Lib;

use \Memcache;
use Lib\env;

class Cmemcache
{
    protected  static \Memcache $memCache;
    public static function __init__()
    {
        self::$memCache ?? self::$memCache = new \Memcache();
        self::$memCache->addServer(env('MEMCACHE_HOST'));
    }

    /**
     * @param string $key
     * @return mixed
     */
    private static function check(string $key):mixed
    {
        return self::$memCache->get($key);
    }

    /**
     * void
     */
    private static function connect():void
    {
        self::__init__();
        self::$memCache->connect(env("MEMCACHE_HOST"),env("MEMCACHE_PORT"));
    }

    /**
     * void
     */
    private static function close():void
    {
        self::$memCache->close();
    }

    /**
     * @param string $key
     * @param int $seconds
     * @param callable $callback
     * @return array
     */
    public static final function remember(string $key, int $seconds, callable $callback):mixed
    {
        self::connect();

        $data = self::check($key);

        if($data === false)
        {
            $data = $callback();
            self::$memCache->set($key, $data, MEMCACHE_COMPRESSED, $seconds);
        }
        return $data;
    }


    /**
     * @param string $key
     */
    public static final function forget(string $key):void
    {
        self::connect();
        self::$memCache->delete($key);
        self::close();
    }

}