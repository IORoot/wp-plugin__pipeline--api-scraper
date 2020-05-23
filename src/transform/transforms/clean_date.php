<?php

namespace yt\transform;

use yt\interfaces\transformInterface;

class clean_date implements transformInterface
{
    
    public $description = "Formats the date to [ Y-m-d H:i:s ]";

    public $parameters = 'None';

    public $input;
    
    public function config($config)
    {
        return;
    }

    public function in($input)
    {
        $this->input = $input;
        return;
    }

    public function out()
    {
        $date = new \DateTime($this->input);
        return $date->format('Y-m-d H:i:s');
    }

}
