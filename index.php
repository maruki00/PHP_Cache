<?php


require_once "vendor/autoload.php";
use Lib\Cache;


$res = Cache::remember("key",20,function(){
    echo "Not From Cache";
	return [12,212,12,12];
});

var_dump($res);


