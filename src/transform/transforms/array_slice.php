<?php

namespace yt\transform;

use yt\interfaces\transformInterface;

class array_slice implements transformInterface
{

    public $description = "Slices specified length of array, from offset. Use for removing items off array. See https://www.php.net/manual/en/function.array-slice.php";

    public $parameters = '(int) offset, (int|null) length, (bool) preserve_keys';

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
        return array_slice($this->input, $this->config);
    }
}
