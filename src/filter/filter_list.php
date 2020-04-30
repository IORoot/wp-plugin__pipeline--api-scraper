<?php

namespace yt;

class filter_list {


    public $list = [];

    public $catalog = [];


    public function __construct(){
        $this->get_filter_list();
        $this->catalog();
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


    public function catalog()
    {
        
        $files = scandir(__DIR__ . '/filters');

        foreach ($files as $file){

            if ($file == '.' || $file == '..'){ continue; }

            $classname = '\\yt\\filter\\'.str_replace('.php', '', $file);
            $name = str_replace('.php', '', $file);
            $instance = new $classname;

            $this->catalog["${name}"]['name'] = $name;
            $this->catalog["${name}"]['classname'] = $classname;
            $this->catalog["${name}"]['file'] = $file;
            $this->catalog["${name}"]['description'] = $instance->description;
            $this->catalog["${name}"]['parameters'] = $instance->parameters;

        }

        return;

    }



}