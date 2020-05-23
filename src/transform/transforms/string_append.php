<?php

namespace yt\transform;

use yt\interfaces\transformInterface;

class string_append implements transformInterface
{

    public $description = "Append a string to the end of the value";

    public $parameters = '(string)';

    public $input;

    public $config;

    public function config($config)
    {
        $this->config = $config;
        return;
    }

    public function in($input)
    {
        $this->input = $input;
        return;
    }

    public function out()
    {
        return $this->input . $this->config;
    }
}
