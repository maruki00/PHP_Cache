<?php
require_once 'env.php';
spl_autoload_register(function($class){
    $className = str_replace('Lib\\','',$class);
    $className = strtolower($className);
    $fullPath = __DIR__.'/'.$className.'.php';
    if(file_exists($fullPath))
    {
        require_once $fullPath;
    }
});