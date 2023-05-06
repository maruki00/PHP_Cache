<?php


//function env(string $key,mixed $default=''){
//    $fileData = fopen('./.env','a+');
//    $ret = $default;
//    $line = "";
//    $lines = explode("\n",file_get_contents('./.env'));
////    array_walk($lines,function($line) use($key) {
////        if($line[0] !== '#' && !empty(trim($line))){
////            $splitedLine = explode('=',$line);
////            if(trim($splitedLine[1]??'') === $key){
////                echo ($splitedLine[1]."<br>");
//////                return  $splitedLine[1]??'';
////            }
////        }
////    });
//
//    foreach($lines as $line)
//    {
//        echo "<br>---- $line ----<br/>";
//        if(trim($line[0]) === "#" || empty($line)) continue;
//        $splitedLine = explode('=',$line);
//        if($splitedLine[0]??'' === $key) {
//            echo "$key => $splitedLine[0] | $splitedLine[1] => $line<br/>";
//            return $splitedLine[1]??'';
//
//        }
//    }
//    die;
////    foreach($lines as $line){
////        echo "$line<br>";
////        if($line[0] !== '#' && !empty(trim($line))){
////            $splitedLine = explode('=',$line);
////            if($splitedLine[1]??'' === $key){
////                echo ($splitedLine[1]."<br>");
////                $ret = $splitedLine[1]??'';
////                break;
////            }
////        }
////    }
//    return trim($ret);
//}


function env($key, $default=''){
    $data = [];
    if ($file = fopen(".env", "r")) {
        while(!feof($file)) {
            $line = fgets($file);
            if($line[0] == '#' || empty(trim($line))) {
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