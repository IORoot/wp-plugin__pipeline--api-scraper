<?php

namespace yt\transform;

use yt\interfaces\transformInterface;

class string_explode implements transformInterface
{

    public $description = "Explode a string into an array.";

    public $parameters = '(string) Delimiter';

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
        return explode($this->config, $this->input);
    }
}
