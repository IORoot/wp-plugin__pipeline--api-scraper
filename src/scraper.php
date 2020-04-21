<?php

namespace yt;

use \yt\options;
use \yt\api;

class scraper {

    public $options;

    public function __construct(){

        $this->options = new options;

        $this->yt = new api;

        return;
    }

}