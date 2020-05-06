<?php

namespace yt\transform;

use yt\interfaces\transformInterface;

class string_remove implements transformInterface
{

    public $description = "Removes a string from a field using str_replace()";

    public $parameters = '(string) Fortnite';

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
        return str_replace($this->config, '', $this->input);
    }
}
