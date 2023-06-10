<?php


namespace Lib;
use Lib\env;


class Cache{
    static public function remember(string $key, int $seconds, callable $callable)
    {
        $driver = sprintf("c%s",env('DRIVER', 'file'));
        $driver = ucfirst(trim($driver));
        return "Lib\\$driver"::remember($key, $seconds,  $callable);
    }
    static public function forget(string $key){
        $driver = env('DRIVER', 'file');
        $driver = ucfirst(trim($driver));
        "$driver"::forget($key);
    }
}