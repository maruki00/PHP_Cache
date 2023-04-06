<?php


spl_autoload_register(function($class){
    $className = str_replace('OULDEVELOPER','',$class);
    $className = strtolower($className);
    $fullPath = __DIR__.'/'.$className;
    if(file_exists($fullPath)){
        require_once $fullPath;
    }
});