<?php

const FILE_PATH = __DIR__."/../.env";
function env($key, $default=''){
    $data = [];
    if ($file = fopen(FILE_PATH , "r")) {
        while(!feof($file)) {
            $line = fgets($file);
            if(empty(trim($line)) || $line[0] == '#') {
                continue;
            }
            $tmp = explode(":",$line);
            $k = trim($tmp[0]);
            if(isset($k) && isset($tmp[1])) {
                $data[$k] = trim($tmp[1]);
            }
        }
        if(isset($data[trim($key)]))
            return $data[$key];
        else
            return $default;
    }
}