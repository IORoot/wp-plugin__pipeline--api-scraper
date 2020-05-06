<?php

namespace yt\housekeep;

use yt\interfaces\housekeepInterface;

class none implements housekeepInterface {

    public function __construct()
    {
        return $this;
    }

    public function wp_query($config)
    {
    }

    public function run()
    {
    }

    public function result()
    {
        return true;
    }

}