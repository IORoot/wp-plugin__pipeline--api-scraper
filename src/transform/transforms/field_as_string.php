<?php

namespace yt\transform;

use yt\interfaces\transformInterface;

class field_as_string implements transformInterface
{
    
    public $description = "Use value of field source as a literal string rather than a reference.";

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
        return (string) $this->input;
    }

}
