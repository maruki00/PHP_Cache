<?php


require_once "vendor/autoload.php";
use Lib\Cache;


$res = Cache::remember("key",20,function(){
    echo "From Server<br>";
	return [12,212,12,12];
});

echo "From Cache<br>";
var_dump($res);


