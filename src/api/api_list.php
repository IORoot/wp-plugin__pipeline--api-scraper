<?php

namespace yt;

class api_list
{
    public $list = [];


    public function __construct()
    {
        $this->get_api_directories();
        $this->associative_to_normal_array();
        return $this->list;
    }

    
    public function get_api_directories()
    {
        $list = scandir(__DIR__);

        foreach($list as $dir) {
            if (is_dir(__DIR__ . '/' .$dir))
            {
                $this->list[$dir] = basename($dir);
            }
        }

        // Unset the 'tokens' directory.
        if (isset($this->list['tokens'])){
            unset($this->list['tokens']);
        }

        if (isset($this->list['.'])){
            unset($this->list['.']);
        }

        if (isset($this->list['..'])){
            unset($this->list['..']);
        }

    }


    public function associative_to_normal_array()
    {
        $this->list = array_values($this->list);
    }

}
