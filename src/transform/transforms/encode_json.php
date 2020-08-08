<?php

namespace yt\transform;

use yt\interfaces\transformInterface;

class encode_json implements transformInterface
{
    
    public $description = "JSON Encodes input.";

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
        return json_encode($this->input);
    }

}
