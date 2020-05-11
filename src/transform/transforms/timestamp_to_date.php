<?php

namespace yt\transform;

use yt\interfaces\transformInterface;

class timestamp_to_date implements transformInterface
{
    
    public $description = "Formats a unix timestamp to [ Y-m-d H:i:s ]";

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
        return date('Y-m-d H:i:s', $this->input);
    }

}
