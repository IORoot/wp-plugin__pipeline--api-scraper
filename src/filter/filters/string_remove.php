<?php

namespace yt\filter;

use yt\interfaces\filterInterface;

class string_remove implements filterInterface
{

    public $description = "Removes a string from a field.";

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
        return;
    }
}
