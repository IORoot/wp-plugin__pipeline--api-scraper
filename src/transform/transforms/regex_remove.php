<?php

namespace yt\transform;

use yt\interfaces\transformInterface;

class regex_remove implements transformInterface
{

    public $description = "Perform a REGEX on the string and remove the matches. preg_replace()";

    public $parameters = '(string) /^[\S].*/';

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
        return preg_replace($this->config, '', $this->input);
    }
}
