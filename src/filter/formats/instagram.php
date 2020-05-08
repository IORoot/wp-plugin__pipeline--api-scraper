<?php

namespace yt\filter\format;

class instagram
{
    public $items;

    public function __construct($items)
    {
        $this->items = $items;
        return $this;
    }
}