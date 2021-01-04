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

        $result = [];

        if (is_array($this->input))
        {
            foreach ($this->input as $input)
            {
                $result = array_merge($result, explode($this->config, $input));
            }
        }

        if (is_string($this->input))
        {
            $result = array_merge($result, explode($this->config, $this->input));
        }

        return $result;
    }
}
