<?php

namespace Lib;

use Lib\env;

class Cfile{

    public string $path = '';
    public function __construct(){
        $this->path = env('FILE_PATH','./cache');
    }

    private static function __init(string $path, string $file)
    {
        if(!is_dir($file)){
            @mkdir($path,0754,true);
        }
        if(!file_exists("$path/$file")){
            @touch($file);
        }
    }

    private static function isValid(string $key, int $seconds):mixed
    {
        $path = env('FILE_PATH','./cache/');
        $file = sprintf("%s/%s",$path,$key);
        self::__init($path, $key);
        return file_exists($file) && (time()-filemtime($file))<=0;
    }

    public static function remember(string $key, int $seconds, callable $func):mixed
    {
        $path = env('FILE_PATH','./cache/');
        $file = sprintf("%s%s",$path,$key);
        $stillValid = self::isValid($key,$seconds);
        if(!$stillValid)
        {
            unlink($file);
            self::__init($path, $key);
            $data = $func();
            file_put_contents($file,serialize($data));
            return $data;
        }
//        var_dump(file_get_contents($file));
        return unserialize(file_get_contents($file));
    }

    public final static function forever(string $key, callable $func) : mixed
    {
        $path = self::getCachePath();
        $file = sprintf("%s/%s",$path,$key);
        if(!file_exists($file)){
            self::__init($path, $key);
            $data = $func();
            file_put_contents($file,serialize($data));
            return $data;
        }
        return unserialize(file_get_contents($file));
    }

    

    public static function forget(string $key)
    {
        $path = env('FILE_PATH','./cache/');
        $file = sprintf("%s%s",$path,$key);
        if(file_exists($file))
            unlink($file);
    }
}
