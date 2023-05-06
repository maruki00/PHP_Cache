<?php


require_once "loader.php";
use Lib\Cache;
use Lib\env;

$res = Cache::remember("key",20,function(){
    echo "Not From Cache";
	return [12,212,12,12];
});

echo var_dump($res);


