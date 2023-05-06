<?php


namespace Lib;
use Lib\env;
use Lib\Mysql;
use Lib\File;

class Cache{
    static public function remember(string $key, int $seconds, callable $callable){
        $driver = env('DRIVER', 'file');
        $driver = ucfirst(trim($driver));
        return "Lib\\$driver"::handle($key, $seconds,  $callable);
    }
    static public function forget(string $key){
        $driver = env('DRIVER', 'file');
        $driver = ucfirst(trim($driver));
        "Lib\\$driver"::forget($key);
    }
}