<?php

namespace yt\transform;

use yt\interfaces\transformInterface;

class array_implode implements transformInterface
{

    public $description = "Implode an Array with separator string.";

    public $parameters = 'Spearator';

    public $input;

    public $config;

    public function config($config)
    {
        $this->config = $config;
    }

    public function in($input)
    {
        $this->input = $input;
    }

    public function out()
    {
        return implode($this->input, $this->config);
    }
}
