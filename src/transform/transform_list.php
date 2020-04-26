<?php

namespace yt;

class transform_list {


    public $list = [];


    public $description = [];


    public $descriptions = [];


    public function __construct(){
        $this->get_transform_list();
        return;
    }

    
    public function get_transform_list(){

        $files = scandir(__DIR__ . '/transforms');

        foreach ($files as $file){
            $file = str_replace('.php', '', $file);
            $file_array["${file}"] = $file;
        }

        unset($file_array['.']);
        unset($file_array['..']);

        $this->list = $file_array;

        return;
    }


    public function get_all_transform_descriptions()
    {
        
        $files = scandir(__DIR__ . '/transforms');
        foreach ($files as $file){

            if ($file == '.' || $file == '..'){ continue; }

            include(__DIR__ . '/'.$file);

            $classname = '\\yt\\filter\\'.str_replace('.php', '', $file);

            $instance = new $classname;

            $description_array["${file}"] = $instance->description;
        }


        $this->descriptions = $description_array;

        return $this->descriptions;

    }

    

}