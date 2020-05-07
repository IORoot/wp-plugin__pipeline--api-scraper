<?php

namespace yt\transform;

use yt\interfaces\transformInterface;

class string_prepend implements transformInterface
{

    public $description = "Prepend a string to the front of the value";

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
        return $this->config . $this->input;
    }
}
