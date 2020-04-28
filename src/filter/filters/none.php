<?php

namespace yt\filter;

use yt\interfaces\filterInterface;

class none implements filterInterface
{
    
    public $description = "Does nothing.";

    public $parameters = "None";

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
        return $this->input;
    }

}
