<?php

namespace yt;

use yt\interfaces\filterInterface;

class filter_list {


    public $list = [
        'none' => 'none',
    ];


    public function __construct(){
        $this->get_filter_list();
        return;
    }

    
    public function get_filter_list(){

        $files = scandir(__DIR__ . '/filters');

        foreach ($files as $file){
            $file = str_replace('.php', '', $file);
            $file_array["${file}"] = $file;
        }

        unset($file_array['.']);
        unset($file_array['..']);

        $this->list = $file_array;

        return;
    }


    

}