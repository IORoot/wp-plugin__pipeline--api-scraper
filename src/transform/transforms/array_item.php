<?php

namespace yt\transform;

use yt\interfaces\transformInterface;

class array_item implements transformInterface
{

    public $description = "Returns an item in an array";

    public $parameters = '(int) Array item. Array[$config]';

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
        return $this->input[$this->config];
    }
}
