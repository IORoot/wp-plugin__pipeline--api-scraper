<?php

namespace yt\transform;

use yt\interfaces\transformInterface;

class string_capitalise implements transformInterface
{

    public $description = "Capitalise first word";

    public $parameters = '';

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
        return ucfirst($this->input);
    }
}
