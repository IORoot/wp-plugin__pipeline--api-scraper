<?php

namespace yt\filter;

use yt\interfaces\filterInterface;

class none implements filterInterface
{
    public $description = "Does nothing.";

    
    public function config($config)
    {
        return;
    }

    public function in($input)
    {
        return;
    }

    public function out()
    {
        return;
    }

}
