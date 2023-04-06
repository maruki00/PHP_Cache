<?php



function _Env($key,$default=''){
    $fileData = fopen('./.env','a+');
    $ret = $default;
    while($line = fscanf($fileData,'%[^\n]s')){
        if($line[0] !== '#'){
            $splitedLine = explode('=',$line);
            if($splitedLine[1]??'' == $key){
                $ret = $splitedLine[1]??'';
                break;
            }
        }
    }
    return $ret;
}