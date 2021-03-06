<?php

namespace yt\transform;

use yt\interfaces\transformInterface;

class clean_date implements transformInterface
{
    
    public $description = "Formats the date to any date format";

    public $parameters = 'Y-m-d H:i:s';

    public $input;

    public $config = 'Y-m-d H:i:s';
    
    public function config($config)
    {
        if (!empty($config)){
            $this->config = $config;
        }
    }

    public function in($input)
    {
        $this->input = $input;
    }

    public function out()
    {
        $date = new \DateTime($this->input);
        return $date->format($this->config);
    }

}
