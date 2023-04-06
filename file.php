<?php

namespace OULDEVELOPER\File;

class File{

    public string $path = '';
    public function __construct(){
        $this->path = \_Env('FILE_PATH','./cache.bin');
    }
}