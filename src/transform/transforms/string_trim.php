<?php

namespace yt\transform;

use yt\interfaces\transformInterface;

class string_trim implements transformInterface
{

    public $description = "Trim a string to the desired length.";

    public $parameters = '(int) 55';

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
        return substr($this->input, 0, $this->config);
    }
}
