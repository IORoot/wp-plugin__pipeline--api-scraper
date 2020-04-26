<?php

namespace yt\transform;

use yt\interfaces\transformInterface;

class none implements transformInterface
{
    
    public $description = "Does nothing.";

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
