<?php

namespace yt\transform;

use yt\interfaces\transformInterface;

class string_parsedown implements transformInterface
{

    public $description = "Parse a string for markdown.";

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
        $Parsedown = new \Parsedown();
        return $Parsedown->text($this->input);
    }
}
